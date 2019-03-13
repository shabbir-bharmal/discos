<?php

use Carbon\Carbon;

class AdminScheduledEmails implements ScheduledEmailsInterface
{
    const TIME_DELAY = 'P21D'; // 21 days
    
    private $today;
    
    function __construct()
    {
        $this->today = (new DateTime)->format('Y-m-d');
    }
    
    public function get_data_for_emails(EmailTemplate $scheduled_email)
    {
        $email_data = array();

        # get data for email                
        switch($scheduled_email->data):

            case 'unpaid':
                
                $email_data['unpaid_deposits'] = (new BookingRepository())->getAllBookingsForUnpaidDeposits()->filter(function($booking){
                    
                    $booking_date = Carbon::createFromFormat('d-m-Y', $booking->date);
                    $date_in_21_days = Carbon::now()->addDays(21);
                    
                    return $booking_date->lte($date_in_21_days);
                });
                
                $email_data['unpaid_balances_coming_up'] = (new BookingRepository())->getAllBookingsForUnpaidBalances()->filter(function($booking){                    
                    
                    $booking_date = Carbon::createFromFormat('d-m-Y', $booking->date);
                    $date_in_21_days = Carbon::now()->addDays(21);
                    
                    return $booking_date->lte($date_in_21_days);
                });

                break;
            case 'future_bookings':
                $email_data['future_bookings'] = (new BookingRepository())->getAllBookingsForTheFuture();

                break;
            case 'current':
            default:
                // shouldn't get here
                break;

        endswitch;
        
        return array( $email_data );
    }

    public function get_pending_emails()
    {
        return ScheduledEmails::getPendingEmails(new DateTime)->filter(function($email) {
            return $email->recipient != EmailTemplate::RECIPIENT_CLIENT;
        });
    }

    public function get_subject($template_subject, Booking $booking = null)
    {
        return $template_subject;
    }
}

?>
