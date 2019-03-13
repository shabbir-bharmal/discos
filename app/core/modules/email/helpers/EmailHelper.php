<?php

namespace Helpers;

class EmailHelper
{
    
    public static function get_email_viewname(\EmailTemplate $email)
    {
        return "templates.emails.$email->view";
    }
    
    public static function sendQuotation($to, $email_template, $data, $client, $booking, $email)
    {
        \Mail::send($email_template, $data, function ($mail) use ($to, $client, $booking, $email,$email_template) {
             
            $mail->to($to);
            if ($email->cc != '') {
                $mail->cc($email->cc);
            }
            $mail->from($email->email_from, $email->name_from);
            $subject = "$email->subject | $booking->venue_name | ***$booking->date $client->name***";
           
            if ($booking->ref_no != '') {
                $subject .= " | $booking->ref_no";
            }
            if($email->subject=='discoscouk' || $email->subject== 'DISCOSCOUK' || str_contains($email_template,'DISCOSCOUK'))
            {
                $subject = "$email->subject";
            }
            
            $mail->subject($subject);
        });
    }
    
    public static function get_all_email_address_settings()
    {
        //todo: get all key like email_*, all distinct values
        return array(
            \Setting::where('key', '=', 'admin_email')->first()->value,
            \Setting::where('key', '=', 'staff_email')->first()->value,
        );
    }
}
