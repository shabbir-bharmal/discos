<?php

namespace Core\Modules\Booking;

use Illuminate\Support\ServiceProvider;
use Event;
use Booking;
use ScheduledEmails;
use Config;
use StatusChangedEmails;

class BookingServiceProvider extends ServiceProvider
{

    public function register()
    {        
        Event::listen('booking.status_changed', function($before, $after, $booking) {
            (new ScheduledEmails(new StatusChangedEmails($booking), ScheduledEmails::LIVE_MODE))->run();            
        });        
    }
    
    public function boot()
    {
        Booking::updating(function($booking) {

            if ($booking->status == 'booking-no-email') {

                $booking->status = Booking::STATUS_BOOKING;

            } else if ($booking->getOriginal('status') == Booking::STATUS_PENDING && $booking->status == Booking::STATUS_BOOKING) {

                \Log::info('sending email');

                Event::fire('booking.status_changed',[
                    'before' => Booking::STATUS_PENDING,
                    'after' => Booking::STATUS_BOOKING,
                    'booking' => $booking
                ]);

            }
        });
        
    }

}
