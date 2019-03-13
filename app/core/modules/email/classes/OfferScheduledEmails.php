<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use \Helpers\DateTimeHelper;

class OfferScheduledEmails implements ScheduledEmailsInterface
{
    public function get_data_for_emails(\EmailTemplate $scheduled_email)
    {
        
        $offer_email = \EmailOffer::where("template_id", $scheduled_email->id)->first();
        Log::info('run_hour -- ');
        Log::info($offer_email->run_hour);
        Log::info(Carbon::now()->hour);
        Log::info(Carbon::now()->format('d-m-Y'));
        
        if ($offer_email->date != Carbon::now()->format('d-m-Y')) {
            return [];
        }


        if ($offer_email->run_hour != Carbon::now()->hour) {
            return [];
        }

        $clients_to_send_to = new Collection();

        if(($offer_email->from_date != "" || $offer_email->from_date != "") && ($offer_email->end_date != "" || $offer_email->end_date != ""))
        {
            $clients_to_send_to = (new BookingRepository())->getAllBookingsBetweenDates(Carbon::createFromFormat('d-m-Y', $offer_email->from_date), Carbon::createFromFormat('d-m-Y', $offer_email->end_date));
        }
        elseif (($offer_email->from_date != "" || $offer_email->from_date != "") && !($offer_email->end_date != "" || $offer_email->end_date != "")) {
            $clients_to_send_to = (new BookingRepository())->getAllBookingsAfterTheDate(Carbon::createFromFormat('d-m-Y', $offer_email->from_date));
        }
        elseif (!($offer_email->from_date != "" || $offer_email->from_date != "") && ($offer_email->end_date != "" || $offer_email->end_date != "")) {
            $clients_to_send_to = (new BookingRepository())->getAllBookingsBeforTheDate(Carbon::createFromFormat('d-m-Y', $offer_email->end_date));
        }

   
        
        // filter by packages
        return $clients_to_send_to->filter(function ($booking) use ($scheduled_email) {
            return in_array($booking->package_id, json_decode(json_encode($scheduled_email->packages), true));
        });

    }

    public function get_pending_emails()
    {
        return EmailTemplate::where('type', '=', EmailTemplate::TYPE_OFFER)
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
