<?php

class StatusChangedEmails implements ScheduledEmailsInterface
{
    private $booking;
    
    public function __construct(\Booking $booking)
    {
        $this->booking = $booking;
    }
    
    
    public function get_data_for_emails(\EmailTemplate $scheduled_email)
    {
        return in_array($this->booking->package_id, json_decode(json_encode($scheduled_email->packages), true)) ? [$this->booking] : array();
    }

    public function get_pending_emails()
    {
        return EmailTemplate::where('type', '=', EmailTemplate::TYPE_EVENT_STATUS_CHANGED)
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
