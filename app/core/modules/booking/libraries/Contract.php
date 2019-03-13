<?php

class Contract
{
    private $messages;

    protected $settings;
    protected $client_email;
    protected $data;
    protected $booking_obj;

    function __construct()
    {
        require_once "vendor/autoload.php";
        if (!defined('DOMPDF_ENABLE_AUTOLOAD')) define('DOMPDF_ENABLE_AUTOLOAD', false);
        require_once 'vendor/dompdf/dompdf/dompdf_config.inc.php';
    }

    public static function make($booking_id)
    {
        $contract = new Contract();

        $contract->contract_settings = \Config::get('contracts');
        $contract->booking_obj = \Booking::find($booking_id);
        $contract->messages = new \Illuminate\Support\MessageBag();

        return $contract;
    }

    public function send()
    {
        if ($this->contract_settings['testing'] && $this->contract_settings['pretend']) {
            Log::info('Pretending to send contract');
            return true;
        }

        $client_email = ($this->contract_settings['testing']) ? $this->contract_settings['test_client_email'] : $this->booking_obj->client->email;

        $this->data['booking'] = $this->booking_obj;

        // get contract html
        $html = \View::make('templates.contracts.contract', $this->data);

        if (isset($html)) {

            if (get_magic_quotes_gpc())
                $html = stripslashes($html);

            ini_set("memory_limit", "100M");

            $dompdf = new \DOMPDF();
            $dompdf->load_html($html);
            $dompdf->set_paper('a4', 'portrait');
            $dompdf->render();
            $pdf = $dompdf->output();
            #$filename = dirname(__FILE__) . "/../contracts/" . $this->contract_settings['filename_prepend'] . time() . ".pdf";
            $filename = $this->contract_settings['filename_prepend'] . time() . ".pdf";
            file_put_contents(dirname(__FILE__) . "/../contracts/" . $filename, $pdf);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://api.signable.co.uk/v1/envelopes');

            // send data
            //$title = urlencode($this->contract_settings['email_title']);
			//$title = urlencode($this->data['booking']->client->name . ' - ' . $this->contract_settings['email_title']);
			$title = urlencode($this->contract_settings['email_title'] . ' | ' . $this->data['booking']->venue_name . ' | ***' . $this->data['booking']->date . ' ' . $this->data['booking']->client->name . '***');

            $documentsArray = array();

            $document = new \stdClass();
            $document->document_title = $this->contract_settings['contract_title'];
            $document->document_url = \Config::get('app.url') . '/app/core/modules/booking/contracts/' . $filename;

            $documentsArray[] = $document;
            $documents = urlencode(json_encode($documentsArray));

            $partyClient = new \stdClass();
            $partyClient->party_name = $this->data['booking']->client->name;
            $partyClient->party_email = $client_email;
            $partyClient->party_role = "signer1";
            $partyClient->party_message = "Please find enclosed your booking confirmation and contract for DJ Nick Burrett.  If you have any questions, please do not hesitate to call us on 01823 240300.";

            $partyDj = new \stdClass();
            $partyDj->party_name = "DJ Nick Burrett";
            $partyDj->party_email = $this->contract_settings['dj_email'];
            $partyDj->party_role = "signer2";
            $partyDj->party_message = "Please find enclosed your booking confirmation and contract for DJ Nick Burrett.  If you have any questions, please do not hesitate to call us on 01823 240300.";

            $partiesArray = array();
            $partiesArray[] = $partyDj;
			$partiesArray[] = $partyClient;
            $parties = urlencode(json_encode($partiesArray));

            $postfields = 'envelope_title=' . $title . '&envelope_documents=' . $documents . '&envelope_parties=' . $parties . '&envelope_auto_remind_hours=24';

            curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
            // Set your API key
            curl_setopt($curl, CURLOPT_USERPWD, $this->contract_settings['signable_api_key'] . ":x");
            // Signable is a safe peer
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            // Don't output the content right away
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            // Place the result into a variable to work with.
            $response = json_decode(curl_exec($curl));
            // Close the cURL request.
            curl_close($curl);
            if ($response->http == '202' || $response->http == '200') {
                $msg = "The contract either has or soon will be sent to the client";
                return true;
            } else if (\App::environment() == 'local') {
                $this->data['error']['message'] = $response->message;
                return \View::make('errors.general_error', $this->data);
            } else {
                // notify nick of error
                $msg = "An error occured whilst trying to send an envelope with Signable. <br/>
            
                Client: " . $this->data['booking']->client->name . "<br/>
                Client email: " . $this->data['booking']->client->email . "<br/><br/>
                Post fields: " . $postfields . "<br/><br/>
                Full error details below:<br/>" . $response->message;

                mail($this->contract_settings['errors'], "Contract error " . $response->code, $msg);
                return false;
                # return \View::make('errors.general_error', $this->data);
            }
        }
    }

    public function messages()
    {
        return $this->messages;
    }
}

?>