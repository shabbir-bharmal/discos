<?php

class EmailToPendingTest extends TestCase
{


    /** test  */
    public function bug()
    {
        $this->emails_sent = array();

        Event::listen('mailer.sending', function($mail){
            $this->emails_sent[] = $mail;
        });


        // mile's pending booking
        $booking = Booking::findOrFail(1427);

        #$this->assertEquals('pending', $booking->status);

        $event = new PartyFinishedEventEmails();

        // run at midnight
        $data = $event->setTime(\Carbon\Carbon::createFromDate(2015, 8, 9)->setTime(0, 0, 0))->get_data_for_emails($booking->package->emailTemplate);

        dd($data);
    }
}