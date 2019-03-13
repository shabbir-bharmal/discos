<?php namespace Core\Modules\Booking\Controller;

use Input;
use Booking;
use TimeSlots;
use Costs;
use BookingHandler;
use Carbon\Carbon;
use CoolKidsConfirm;
use Illuminate\Support\Facades\Log;

class BookingApi extends \ApiController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        // nothing
	}

    public function blockQuotes($date, $postcode)
    {
        $valid = TimeSlots::validate($date, $postcode);

        if ($valid !== true) {
            return $this->replyError($valid, 400);
        }

        $time_slots = TimeSlots::get_slots_to_check($date);

        $slots = [];

        foreach($time_slots as $time_slot) {

            $availability = \Availability::make( array (
                'date' => $time_slot['date'],
                'start_timestamp' => $time_slot['start_timestamp'],
                'finish_timestamp' => $time_slot['finish_timestamp'],
                'package_name' => \BlockQuotes::getPackageName(),
                'postcode' => $postcode,
                'debug' => false,
                'name_comparison' => 'like'
            ) );

            $available = $availability->is_free();

            #if (!$available && $this->debug) echo "Not available for ".(new DateTime())->setTimestamp($slot_to_check['start_timestamp'])->format('d-m-Y H:i:s'). "\n";

            if ($available) {
                $booking = new Booking();
                $booking->date = $time_slot['date']->format('m-d-Y');
                $booking->start_time = Carbon::createFromTimestamp($time_slot['start_timestamp'])->format('H:i:s');
                $booking->finish_time = Carbon::createFromTimestamp($time_slot['finish_timestamp'])->format('H:i:s');
                $booking->venue_postcode = $postcode;
                $booking->package_id = $availability->package_id;
                $booking->setup_equipment_time = \Package::findOrFail($availability->package_id)->setup_time;

                $costs = Costs::make($booking);
                $booking = $costs->set_costs();
            }

            $slots[$time_slot['start_timestamp']] = [
                'date' => $time_slot['date']->format('d-m-Y'),
                'start' => Carbon::createFromTimestamp($time_slot['start_timestamp'])->format('H:i'),
                'finish' => Carbon::createFromTimestamp($time_slot['finish_timestamp'])->format('H:i'),
                'available' => $available ? 1 : 0,
                'total_cost' => $available ? $booking->total_cost : false
            ];

        }

        return $this->replySuccess(['slots' => $slots]);
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $booker = (new BookingHandler(new \ProvisionallyBook(), Input::except('_token')));

        $booker->go();

        if (!$booker->messages->isEmpty()) {
            return $this->replyError($booker->messages, 400);
        }

        return $this->replySuccess(['booking' => $booker->get_booking()]);
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


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

    public function confirm()
    {

        $booker = (new BookingHandler(new \CoolKidsConfirm(Input::get('booking_id', 0)), Input::except('_token')));

        if (!$booker->go()) {
            return $this->replyError($booker->messages, 400);
        }

        return $this->replySuccess(['booking' => $booker->get_booking()->load('client')]);
    }

    public function setPayment()
    {
    	Log::info(Input::get('booking_id'));

        $booker = (new BookingHandler(new \Payment(Input::get('booking_id', 0)), Input::except('_token')));

        $booker->go();

        if (!$booker->messages->isEmpty()) {
            return $this->replyError($booker->messages, 400);
        }

        return $this->replySuccess(['booking' => $booker->get_booking()]);
    }


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
