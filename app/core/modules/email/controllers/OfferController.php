<?php

namespace Core\Modules\Email\Controller;

class OfferController extends \BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Offer Emails';
    }
    
    function getIndex()
    {
        $this->data['schedules'] = \EmailOffer::schedules();
        $this->get_relational_data();
        return \View::make('admin.offer', $this->data);
    }

    private function get_relational_data()
    {
        $this->data['delete_available'] = false;
        $this->data['add_available'] = false;
        $this->data['clear_available'] = false;
    }

    function getEdit($id)
    {
        $this->data['schedule'] = \EmailTemplate::find($id);
        $this->get_relational_data();
        return \View::make('admin.offer-edit', $this->data);
    }

    public function getEmailOffer($id)
    {
        $offer = \EmailOffer::find($id);
        $response = array(
            'id' => $id,
            'template_id' => $offer->template_id,
            'booking_type' => $offer->booking_type,
            'from_date' => $offer->from_date,
            'end_date' => $offer->end_date,
            'date' => $offer->date,
            'run_hour' => $offer->run_hour,
            'status' => $offer->status);
        
        return json_encode($response);
    }

    function postEdit()
    {
        $input = \Input::except('_token');   
                
        if (\Input::has('delete')) {
            return $this->deleteSchedule($input['id']);
        } 
        //print_r($input);
        if(\Input::has('id') && $input['id'] != "") {
        	$template = \EmailTemplate::find($input['template_id']);
        	if($template && $template->id){
        		$input['title'] = 	$template->name;
        	}
            $input['status'] = isset($input['status']) ? 1 : 0;
            \EmailOffer::find($input['id'])->update($input);
        }else {

        	$template = \EmailTemplate::find($input['template_id']);
        	//print_r($template); die;
        	if($template && $template->id){
        		$input['title'] = 	$template->name;
        	}
        	$input['status'] = isset($input['status']) ? 1 : 0;
        	$email_offer = new \EmailOffer();
        	$email_offer = $email_offer->fill($input);
            $email_offer->save();
        }
        
        return \Redirect::to('admin/email-offers');
    }


}
