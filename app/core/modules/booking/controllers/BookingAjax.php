<?php

namespace Core\Modules\Booking\Controller;

use Input;
use Client;
use App;
use \Illuminate\Database\Eloquent\Collection;

class BookingAjax extends \AjaxController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(!Input::has('client_name')) App::abort(404); // only showing client bookings atm
        
        $clients = Client::where('name', '=', Input::get('client_name'))->get();
        
        if ($clients->isEmpty()) {
            return $this->reply(['error' => 'Client not found']);
        }
        
        $bookings = new Collection();
        
        foreach($clients as $client) {
            
            $client_bookings = $client->bookings->filter(function($booking){
                return $booking->deleted == 0 && $booking->status == \Booking::STATUS_BOOKING;
            });
            
            $bookings = $bookings->merge($client_bookings);
        }
        
        return $this->reply(['bookings' => $bookings]);
	}

    public function for_date_range($start, $end, $postcode)
    {
        # get all bookings in range
        $bookings = Booking::confirmed()->get();

        return $this->reply(['bookings'=> $bookings], true);
    }


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


}
