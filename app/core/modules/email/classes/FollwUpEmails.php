<?php

use Carbon\Carbon;
use \Helpers\DateTimeHelper;

class FollowUpEmails implements ScheduledEmailsInterface
{
    public function get_data_for_emails(\EmailTemplate $scheduled_email)
    {
        
        if ($scheduled_email->execution_hour != Carbon::now()->hour) {
            return [];
        }

        $follow_ups = FollowUp::with('booking','client')->get();

        $follow_ups = $follow_ups->filter(function ($follow_up) use ($scheduled_email) {
            
            return $follow_up->booking->status == 'pending' && Carbon::today()->diffInDays($follow_up->created_at->startOfDay()) == 
            $scheduled_email->filter && in_array($follow_up->booking->package_id, $scheduled_email->packages) && ! $follow_up->booking->deleted;
        });

        return $follow_ups;
    }

    public function get_pending_emails()
    {
        return EmailTemplate::where('type', '=', EmailTemplate::TYPE_FOLLOW_UP)
                ->where('deleted', '=', 0)
                ->get();
    }

    public function get_subject($template_subject, $follow_up = null)
    {
        $subject = 'Re: ' . $template_subject . ' | ' . $follow_up->booking->venue_name . ' | *** ' . $follow_up->booking->date . ' ' . $follow_up->client->name . '***';
        if ($follow_up->booking->ref_no != '') {
            $subject .= ' | ' . $follow_up->booking->ref_no;
        }

        return $subject;
    }
}
