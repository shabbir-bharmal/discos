<?php

use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ClientScheduledEmails implements ScheduledEmailsInterface
{
    const DAYS_DELAY = 7;
    const DAYS_BEFORE_ANNIVERSARY = 30;
    
    public function get_data_for_emails(EmailTemplate $scheduled_email)
    {
        $clients_to_send_to = new Collection();

        # get which clients we need to send to
        switch ($scheduled_email->filter) :
            case EmailTemplate::WHEN_WEEK_BEFORE:
                $clients_to_send_to = (new BookingRepository())->getAllBookingsForDate(Carbon::today()->addDays(self::DAYS_DELAY));
                break;
            case EmailTemplate::WHEN_WEEK_AFTER:
                $clients_to_send_to = (new BookingRepository())->getAllBookingsForDate(Carbon::today()->subDays(self::DAYS_DELAY));
                break;
            default:
            case EmailTemplate::WHEN_MONTH_BEFORE_ANNIVERSARY:
                $clients_to_send_to = (new BookingRepository())->getAllBookingsForBookingDate(Carbon::today()->subYear()->addDays(self::DAYS_BEFORE_ANNIVERSARY));
                break;
                // shouldn't get here
                break;
        endswitch;
        
        // filter by packages
        return $clients_to_send_to->filter(function ($booking) use ($scheduled_email) {
            return in_array($booking->package_id, json_decode(json_encode($scheduled_email->packages), true));
        });
    }

    public function get_pending_emails()
    {
        return ScheduledEmails::getPendingEmails(new DateTime)->filter(function ($email) {
            return $email->recipient == EmailTemplate::RECIPIENT_CLIENT;
        });
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
