<?php

use Helpers\DateTimeHelper;
use Illuminate\Support\MessageBag;

class BookingHandler
{

    public $client;
    private $booking;
    public $email;
    public $date; // still needed?
    public $messages;

    private $action;

    private $package;
    private $input;

    public function __construct(BookingActionInterface $action, $input = [])
    {
        $this->action = $action;
        $this->input = $input;
        $this->messages = new MessageBag();
    }

    public function go()
    {
        if (! $this->validate()) {
            return false;
        }

        if (! $this->is_available()) {
            return false;
        }

        \Log::error(json_encode($this->input));

        return $this->action->process($this->input);
    }

    private function validate()
    {
        $validator = Validator::make($this->input, $this->action->get_rules());

        if ($validator->fails()) {
            $this->messages = $validator->messages();
            return false;
        } else if (isset($this->input['date'])) {
            $this->date = new \DateTime($this->input['date']);
        }

        return true;
    }

    private function is_available()
    {
        // all actions' booking are at state pending at most here
        $data = (in_array(get_class($this->action), ['BlockQuotes', 'Quote', 'InternalQuote', 'ProvisionallyBook'])) ? $this->input : $this->action->booking;

        $this->date = new \DateTime($data['date']);
        if (! isset($data['package_name'])) {
            $data['package_name'] = $this->action->booking->package->name;
        }

        list ($start_timestamp, $finish_timestamp) = DateTimeHelper::get_start_end_timestamps(
            $this->date,
            DateTimeHelper::timepicker_to_dbtime($data['start_time']),
            DateTimeHelper::timepicker_to_dbtime($data['finish_time'])
        );

        $availability = Availability::make(array(
            'date' => $this->date,
            'start_timestamp' => $start_timestamp,
            'finish_timestamp' => $finish_timestamp,
            'package_name' => $data['package_name'],
            'postcode' => $data['venue_postcode'],
            'debug' => false,
            'name_comparison' => $this->action->get_site() == CoolKidsParty::SITE ? 'like' : '='
        ));

        #\Log::info('availability obj '.print_r($availability, true));

        if ($free = $availability->is_free()) {
            $this->package = Package::findOrFail($availability->package_id);
            $this->input['package'] = $this->package;
            $this->input['set_id'] = $availability->set_id;
        } else {
            $this->messages->add('availability', $this->action->get_unavailable_text());
        }

        return $free;
    }

    public function get_booking()
    {
        return property_exists(get_class($this->action), 'booking') ? $this->action->booking : $this->booking;
    }

    public function set_input_from_booking(Booking $booking)
    {
        $this->input = $booking->toArray();
        $this->input['package_name'] = $booking->package->name;
        if (isset($this->input['date'])) {
            $this->date = new \DateTime($this->input['date']);
        }

        return $this;
    }
}
