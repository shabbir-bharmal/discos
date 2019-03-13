<?php

use Helpers\DateTimeHelper;
use Carbon\Carbon;

class PartyFinishedEventEmails implements ScheduledEmailsInterface
{
    private $now = null;

    public function get_data_for_emails(\EmailTemplate $scheduled_email)
    {
        $bookings_just_finished = array();

        $this->now = $this->now ?: Carbon::now();
        
        switch ($scheduled_email->filter) :
            case 'event':
                $bookings_just_finished = (new BookingRepository())->getAllBookingsFinishingOnDate($this->now)->filter(function ($booking) {

                    #dd(DateTimeHelper::dbtime_to_hour($booking->finish_time));

                    return DateTimeHelper::dbtime_to_hour($booking->finish_time) == $this->now->format('H');
                });

                break;
            default:
                // shouldn't get here
                break;
        endswitch;
        
        // filter by packages
        return $bookings_just_finished->filter(function ($booking) use ($scheduled_email) {
            return in_array($booking->package_id, json_decode(json_encode($scheduled_email->packages), true));
        });
    }

    public function setTime(Carbon $time)
    {
        $this->now = $time;
        return $this;
    }

    public function get_pending_emails()
    {
        return EmailTemplate::where('type', EmailTemplate::TYPE_EVENT_PARTY_FINISHED)
                ->where('deleted', 0)
                ->get();
    }

    public function get_subject($template_subject, $booking = null)
    {
        $client_name = $booking->client->name;
        if($template_subject == 'discoscouk' || $template_subject == 'DISCOSCOUK')
        {
            return $template_subject;
        }
        return "$template_subject | $booking->venue_name | ***$booking->date $client_name***";
    }
}
