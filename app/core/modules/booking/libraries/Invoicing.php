<?php

use Illuminate\Support\Collection;
use Helpers\QuickBookHelper;

class Invoicing
{        
    private $dataService;
    private $platformService;
    private $errors;

    const OAUTH_REQUEST_URL = 'https://oauth.intuit.com/oauth/v1/get_request_token';
    const OAUTH_ACCESS_URL = 'https://oauth.intuit.com/oauth/v1/get_access_token';
    const OAUTH_AUTHORISE_URL = 'https://appcenter.intuit.com/Connect/Begin';

    function __construct(DataService $dataService, PlatformService $platformService)
    {
        $this->dataService = $dataService;
        $this->platformService = $platformService;
    }
    
    public function errors() {
        return $this->errors;
    }

    public function send(Collection $bookings)
    {
        Log::info('Request made to send an invoice for bookings'. $bookings->toJson());
        
        if (!$this->validate($bookings)) return false;
        
        $client = $this->getClient($bookings->first()->client);
        
        if (!$client) return false;
        
        $invoice = QuickBookHelper::populateInvoice($bookings, $client->qb_customer_ref);
        
        try {
            return $this->dataService->Add($invoice);
        }
        catch(Exception $e) {
            Log::error($e->getTraceAsString());
            $this->errors[] = $e->getMessage();
        }
        
        return false;
    }
    
    /*
     * check all bookings are for the same client
     */
    private function validate(Collection $bookings)
    {
        $client_id = $bookings->first()->client_id;
        
        foreach($bookings as $booking) {
            if ($booking->client_id != $client_id) {
                $this->errors[] = 'Bookings are not for the same client';
                return false;
            }
            
            if ($booking->invoice_amount == 0) {
                $this->errors[] = 'At least one booking has paid fully';
                return false;
            }
            
            if ($booking->status != \Booking::STATUS_BOOKING) {
                $this->errors[] = 'At least one booking does not have a status of booking';
                return false;
            }
        }  
        
        return true;
    }
    
    public function getClient(Client $client)
    {
        #if ($client->qb_customer_ref > 0) return $this->updateClient($client);        
        if ($this->findAndUpdateClient($client)) return $client;
        
        return $this->addClient($client);
    }
    
    private function findAndUpdateClient(Client $client)
    {
        if ($client->qb_customer_ref > 0) {
            $customerObj = $this->readClient($client->qb_customer_ref);            
            if ($customerObj) return $this->updateClient ($client, $customerObj);
        }        
        
        Log::info("Looking for client $client");
        $query = "select * from Customer where DisplayName = '".$client->name."'";
        
        try {
            $resultingCustomerObjs = $this->dataService->query($query);
            
            Log::info('Results: '.print_r($resultingCustomerObjs, true));
            
            if (!$resultingCustomerObjs) return false;
            
            // find one with correct email address
            foreach($resultingCustomerObjs as $resultingCustomerObj) {
                
                if($resultingCustomerObj->PrimaryEmailAddr->Address == $client->email) {                    
                    $client->qb_customer_ref = $resultingCustomerObj->Id;
                    $client->save();
                    return $this->updateClient($client, $resultingCustomerObj);
                }
            }
        } 
        catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
            
        return false;        
    }
    
    private function addClient(Client $client)
    {
        Log::info('About to add customer');
        $customerObj = QuickBookHelper::populateCustomer($client);
        try {
            $resultingCustomerObj = $this->dataService->Add($customerObj);
        } 
        catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
        
        $client->qb_customer_ref = $resultingCustomerObj->Id;
        $client->save();
        return $client;
    }
    
    private function updateClient(Client $client, $customerObj)
    {
        Log::info('About to update customer');
        $customerObj = QuickBookHelper::updateCustomerObject($client, $customerObj);
        try {
            $resultingCustomerObj = $this->dataService->Update($customerObj);
        } 
        catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
        
        return $client;
    }
    
    private function readClient($id)
    {
        Log::info('About to read customer');
        
        $customerObj = new \IPPCustomer();
        $customerObj->Id = $id;
        
        try {
            return $this->dataService->Retrieve($customerObj);
        } 
        catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }
        
        return false;
    }
    
    public function getRecordsByTableName($table_name, $max_results = 100)
    {
        $query = "select * from $table_name StartPosition 1 MaxResults 100";
        
        try {
            return $this->dataService->query($query);
        } 
        catch (Exception $e) {
            $this->errors[] = $e->getMessage();
        }
            
        return false;  
    }

    public function hasTokenExpired()
    {
        // how to do this?
        return false;
    }


    /*
     * where token is between 150 - 180 days old
     */
    public function renewToken()
    {
        try
        {
            return $this->platformService->Reconnect();
        }
        catch(Exception $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }


    }

    /*
     * where token is older than 180
     */
    public function newToken()
    {
        if ( isset($_GET['start'] ) ) {
            unset($_SESSION['token']);
        }

        $oauth = new \OAuth( \Config::get('invoices.ConsumerKey'), \Config::get('invoices.ConsumerSecret'), OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);

        $oauth->enableDebug();
        $oauth->disableSSLChecks();

        if (!isset( $_GET['oauth_token'] ) && !isset($_SESSION['token']) ) {

            // step 1: get request token from Intuit
            $request_token = $oauth->getRequestToken(self::OAUTH_REQUEST_URL, \Invoicing::callback_url());
            $_SESSION['secret'] = $request_token['oauth_token_secret'];

            // step 2: send user to intuit to authorize
            header('Location: ' . self::OAUTH_AUTHORISE_URL . '?oauth_token=' . $request_token['oauth_token']);
        }

        if ( isset($_GET['oauth_token']) && isset($_GET['oauth_verifier']) )
        {
            // step 3: request a access token from Intuit
            $oauth->setToken($_GET['oauth_token'], $_SESSION['secret']);
            $access_token = $oauth->getAccessToken( self::OAUTH_ACCESS_URL );

            $_SESSION['token'] = serialize( $access_token );
            $_SESSION['realmId'] = $_REQUEST['realmId'];  // realmId is legacy for customerId
            $_SESSION['dataSource'] = $_REQUEST['dataSource'];

            // step 4: update configs & email

            /*$token = $_SESSION['token'] ;
            $realmId = $_SESSION['realmId'];
            $dataSource = $_SESSION['dataSource'];
            $secret = $_SESSION['secret'] ;*/


            \Mail::send('emails.qb-renewed', $_SESSION, function (\Illuminate\Mail\Message $mail)  {
                $mail->to(\Config::get('mail.webmaster'));
                $mail->subject('QuickBooks tokens reset');
            });


            // write JS to pup up to refresh parent and close popup
            echo '<script type="text/javascript">
            window.opener.location.href = window.opener.location.href;
            window.close();
          </script>';
        }


    }

    public static function callback_url()
    {
        return \Config::get('app.url') . 'admin/invoices/renew-token';
    }

}

?>
