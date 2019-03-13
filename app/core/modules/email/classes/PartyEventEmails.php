<?php

use \Helpers\DateTimeHelper;

class PartyEventEmails implements ScheduledEmailsInterface
{
    public function get_data_for_emails(\EmailTemplate $scheduled_email)
    {
        $bookings_happening_now = array();
        
        switch ($scheduled_email->filter) :
            case 'event':
                $bookings_happening_now = (new BookingRepository())->getAllBookingsForDate((new DateTime))->filter(function ($booking) {
                    // starting this hour
                    return DateTimeHelper::dbtime_to_hour($booking->start_time) == (new DateTime)->format('H');
                });
                break;
            default:
                // shouldn't get here
                break;
        endswitch;
        
        // filter by packages
        return $bookings_happening_now->filter(function ($booking) use ($scheduled_email) {
            return in_array($booking->package_id, json_decode(json_encode($scheduled_email->packages), true));
        });
    }

    public function get_pending_emails()
    {
        return EmailTemplate::where('type', '=', EmailTemplate::TYPE_EVENT_PARTY)
                ->where('deleted', '=', 0)
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
