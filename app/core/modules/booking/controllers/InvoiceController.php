<?php

namespace Core\Modules\Booking\Controller;

use Input;
use Client;
use Booking;
use View;
use QB;
use Redirect;
use \Illuminate\Support\Collection;

class InvoiceController extends \AdminController
{
    public function getIndex()
    {
        $this->fetchRelationalData();
        return View::make('admin.invoices', $this->data);
    }

   /* public function postIndex()
    {
        if (!Input::has('bookings')) return \Redirect::to('admin/invoices')->withError(new Collection(['No booking supplied']));

        $bookings = Booking::whereIn('id', Input::get('bookings'))->get();

        if (!QB::send($bookings)) {
            return Redirect::to('admin/invoices')->withError(new Collection(QB::errors()));
        }

        return Redirect::to('admin/invoices')->withMessage('Your invoice has been created for ' . $bookings->first()->client->name);
    }*/

    public function postIndex()
    {
        if (!Input::has('bookings')) return \Redirect::to('admin/invoices')->withError(new Collection(['No booking supplied']));

        $bookings = Booking::whereIn('id', Input::get('bookings'))->get();
       $success = false;
       foreach ($bookings as $key => $booking) {
           $success = $this->getCreateContact($booking->client_id, $booking->id );
       }
       //die;
       if($success)
        return Redirect::to('admin/invoices')->withMessage('Your invoice has been created for ' . $bookings->first()->client->name);
    	else
        return Redirect::to('admin/invoices')->withMessage('Error with creating invoice for ' . $bookings->first()->client->name);
    }


    public function getIds()
    {
        # get ids for lists: terms, servcies/products

    }

    public function getCreateContact($clientId, $bookingId)
    {

        $client  = Client::findOrFail($clientId);

        if(!$client){ Redirect::to('admin/invoices')->withMessage('No client record found'); }        

        $zbook = new ZohoBooks(\Setting::getValueFromKey('zoho_authtoken'), \Setting::getValueFromKey('zoho_organization_id'));
        $contact = "";

        //$contacts = $zbook->allContacts();
        //print_r($contacts); die;
        //echo $client->zoho_customer_id; 

        if($client->zoho_customer_id != ""){
        	$contact = $zbook->getContact($client->zoho_customer_id);
        	//print_r($contact); //die;
        	if(!$contact){
        		$client->zoho_customer_id = "";
        	}
        }
        	

        if($client->zoho_customer_id == ""){

             $contact = ["contact_name"=>$client->name,
                        /*"company_name"=>$client->name,*/
                        "billing_address"=> [
                            "attention"=> $client->name,
                            "address"=> $client->address1,
                            "street2"=> $client->address2,
                            "city"=> $client->address3,
                            "state"=> $client->address4,
                            "zip"=> $client->postcode,
                            "country"=> "United Kingdom"
                        ],
                        "contact_persons"=> [
                        [
                          //"first_name"=> $client->name,
                          "email"=>$client->email,
                          "phone"=> $client->telephone,
                          "mobile"=> $client->mobile,
                          "is_primary_contact"=> true
                        ]]];

            $contact = $zbook->addContact(json_encode($contact));
            //echo "sadsd"; die;
            //print_r($contact); //die;

            $client->zoho_customer_id = $contact->contact->contact_id;

            $client->save();
            
        }

        $booking = \Booking::findOrFail($bookingId);

        //print_r($client); die;
        //print_r($booking); die;

        if($booking->date_booked != null){
            $date_booked = explode("-", $booking->date_booked);

            $date_booked = $date_booked[2]."-".$date_booked[1]."-".$date_booked[0];        
        }

        if($booking->date != null){
            $date = explode("-", $booking->date);
            $date1 = $date[0]." ".date("F", strtotime($booking->date))." ".$date[2]; 
            $date = $date[2]."-".$date[1]."-".$date[0];        
        }


        $invoice = ["customer_id"=>$client->zoho_customer_id, 
            
            "date"=> $date_booked,
            "due_date"=>$date, 
            "line_items"=>[
                /*["name"=>"Booking - ".$booking->date." (".$booking->venue_name.")",
                "description"=>"Time :- ".$booking->start_time." - ".$booking->finish_time,*/
                 ["name"=>"Entertainment",
                "description"=>"DJ Nick Burrett | ".$date1." | ".$booking->venue_name,
                "rate"=>$booking->total_cost,
                "quantity"=>1]],
               /* "payment_options"=>[
                    "payment_gateways"=> [
                      [
                        "gateway_name"=> "paypal",
                        "additional_field1"=> "standard"
                      ],
                      [
                        "gateway_name"=> "stripe",
                        "additional_field1"=> ""
                      ]
                    ]
                  ],*/
            "notes"=> "Thanks for your business.",
            "terms"=> "Terms and conditions apply."

        ];

        $invoiceBillingAddress = ["address"=>$client->address1." ".$client->address2." ".$client->address3, 
            "zip"=>$client->postcode,
            "city"=>$client->address4,
            "state"=> $client->address4,
        ];

        return $invoice  = $zbook->postInvoice(json_encode($invoice) , json_encode($invoiceBillingAddress),  true);

    }

    private function fetchRelationalData()
    {
        $this->data['clients'] = Client::where('deleted', '=', 0)->get();
    }

    public function getRenewToken()
    {
        try {
            return \QB::newToken();

        } catch (OAuthException $e) {
            echo "Got auth exception";
            echo '<pre>';
            print_r($e);
        }
    }
}




























/**
 *
 * Zoho Books API
 * Version: 2
 *
 * Author: Giuseppe Occhipinti - https://github.com/peppeocchi
 *
 * CHANGELOG v2
 * - extended parameters for invoices and credit notes and contacts
 *
 */

class ZohoBooks
{
    /**
     * cUrl timeout
     */
    private $timeout = 30;

    /**
     * HTTP code of the cUrl request
     */
    private $httpCode;

    /**
     * Zoho Books API authentication
     */
    private $authtoken;
    private $organizationId;

    /**
     * Zoho Books API request limit management
     */
    private $apiRequestsLimit = 150;
    private $apiRequestsCount;
    private $apiTimeLimit = 60;
    private $startTime;

    /**
     * Zoho Books API urls request
     */
    private $apiUrl = 'https://books.zoho.eu/api/v3/';
    private $contactsUrl = 'contacts';
    private $invoicesUrl = 'invoices/';
    private $creditnotesUrl = 'creditnotes/';

    

    /**
     * Init
     *
     * @param (string) Zoho Books authentication token
     * @param (string) Zoho Books organization id
     */
    public function __construct($authtoken, $organizationId)
    {
        $this->authtoken = $authtoken;
        $this->organizationId = $organizationId;
        $this->apiRequestsCount = 0;
        $this->startTime = time();
    }


    /**
     * Get all contacts
     *
     * @return (string) json string || false
     */
    public function allContacts($config = array())
    {
        echo $url = $this->apiUrl . $this->contactsUrl . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId;
        if(isset($config['page'])) {
            $url .= '&page=' . $config['page'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", 'Authorization: Zoho-authtoken ' + $this->authtoken));
        $contacts = curl_exec($ch);
        //print_r($contacts);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $contacts : false;
    }

    /**
     * Get contact details by ID
     *
     * @param (int) contact id
     *
     * @return (string) json string || false
     */
    public function getContact($id)
    {
        $ch = curl_init();
        //echo $this->apiUrl . $this->contactsUrl . $id . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId;
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->contactsUrl. '/' . $id . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", 'Authorization: Zoho-authtoken ' + $this->authtoken));
        $contact = curl_exec($ch);
        //print_r($contact);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $contact : false;
    }


    /**
     * Get contact details by ID
     *
     * @param (int) contact id
     *
     * @return (string) json string || false
     */
    public function addContact($contact)
    {

        $url = $this->apiUrl . $this->contactsUrl.'?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId;

        $data = array(
            'JSONString'        => $contact
        );

        //$contact["organization_id"] = $this->organizationId;
        //echo $url;
        //print_r($data); 
        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", 'Authorization: Zoho-authtoken '.$this->authtoken));

        $contact = curl_exec($ch);
    
        //print_r(json_decode($contact)); //die;
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();
        //echo $this->httpCode; die;
        return $this->httpCode == 201 ? json_decode($contact) : false;
    }


    /**
     * Get all invoices
     *
     * @param (date) date start
     * @param (date) date end
     *
     * @return (string) json string || false
     */
    public function allInvoices($config = array())
    {
        $url = $this->apiUrl . $this->invoicesUrl . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId;
        if(isset($config['date_start']) && isset($config['date_end'])) {
            $url .= '&date_start=' . $config['date_start'] . '&date_end=' . $config['date_end'];
        }
        if(isset($config['invoice_number_startswith'])) {
            $url .= '&invoice_number_startswith=' . $config['invoice_number_startswith'];
        }
        if(isset($config['page'])) {
            $url .= '&page=' . $config['page'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $invoices = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $invoices : false;
    }


    /**
     * Get invoice
     *
     * @param (int) invoice id
     *
     * @return (string) json string || false
     */
    public function getInvoice($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->invoicesUrl . $id . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $invoice = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $invoice : false;
    }


    /**
     * Create an invoice
     *
     * @param (string) json encoded
     * @param (bool) send the invoice to the contact associated with the invoice
     *
     * @return (bool)
     */
    public function postInvoice($invoice, $invoiceBillingAddress, $send = false)
    {
        $url = $this->apiUrl . $this->invoicesUrl;

        //print_r($invoice);// die;

        $data = array(
            'authtoken'         => $this->authtoken,
            'JSONString'        => $invoice,
            "organization_id"   => $this->organizationId
        );

        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", 'Authorization: Zoho-authtoken ' + $this->authtoken));

        $invoice = curl_exec($ch);
        $invoice = json_decode($invoice);
        //print_r($invoice); //die;
        
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        /*if($this->httpCode == 201){
            $this->addInvoiceAddress($invoice->invoice->invoice_id, $invoiceBillingAddress);    
        }*/
        
        $this->checkApiRequestsLimit();

        return $this->httpCode == 201 ? true : false;
    }


    /**
     * update billling address invoice
     *
     * @param (string) json encoded
     * @param (bool) send the invoice to the contact associated with the invoice
     *
     * @return (bool)
     */
    public function addInvoiceAddress($invoice_id, $address)
    {
        $url = $this->apiUrl . $this->invoicesUrl.$invoice_id."/address/billing";

        //print_r($invoice); die;

        $data = array(
            'authtoken'         => $this->authtoken,
            'JSONString'        => $address,
            "organization_id"   => $this->organizationId
        );

        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_HEADER => false,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true
        ));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", 'Authorization: Zoho-authtoken ' + $this->authtoken));

        $invoice = curl_exec($ch);

        //print_r($invoice); die;
        
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 201 ? true : false;
    }


    /**
     * Get all credit notes
     *
     * @param (date) date start
     * @param (date) date end
     *
     * @return (string) json string || false
     */
    public function allCreditNotes($config = array())
    {
        $url = $this->apiUrl . $this->creditnotesUrl . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId;
        if(isset($config['date_start']) && isset($config['date_end'])) {
            $url .= '&date_start=' . $config['date_start'] . '&date_end=' . $config['date_end'];
        }
        if(isset($config['page'])) {
            $url .= '&page=' . $config['page'];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $creditnotes = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $creditnotes : false;
    }


    /**
     * Get credit note
     *
     * @param (int) credit note id
     *
     * @return (string) json string || false
     */
    public function getCreditNote($id)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $this->creditnotesUrl . $id . '?authtoken=' . $this->authtoken . '&organization_id=' . $this->organizationId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $creditnote = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 200 ? $creditnote : false;
    }


    /**
     * Create a credit note
     *
     * @param (string) json string
     *
     * @return (bool)
     */
    public function postCreditNote($creditnote)
    {
        $url = $this->apiUrl . $this->creditnotesUrl;

        $data = array(
            'authtoken'         => $this->authtoken,
            'JSONString'        => $creditnote,
            "organization_id"   => $this->organizationId
        );

        $ch = curl_init($url);

        curl_setopt_array($ch, array(
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $creditnote = curl_exec($ch);
        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->checkApiRequestsLimit();

        return $this->httpCode == 201 ? true : false;
    }


    /**
     * Get HTTP code
     */
    public function getHttpCode()
    {
        return $this->httpCode ? $this->httpCode : false;
    }


    /**
     * Check API requests limit
     *
     */
    private function checkApiRequestsLimit()
    {
        $tempTime = time() - $this->startTime;
        if($this->apiRequestsCount >= $this->apiRequestsLimit && $tempTime < $this->apiTimeLimit) {
            usleep(($this->apiTimeLimit - $tempTime)*1000000);
            $this->apiRequestsCount = 1;
            $this->startTime = time();
        } else {
            $this->apiRequestsCount++;
        }
    }
}