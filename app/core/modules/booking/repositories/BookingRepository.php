<?php

use Carbon\Carbon;

class BookingRepository
{
    public function getAllBookingsForDate(DateTime $date)
    {
        return Booking::confirmed()
                ->where('date', $date->format('Y-m-d'))
                ->get();
    }
    public function getAllBookingsForBookingDate(DateTime $date)
    {
        return Booking::confirmed()
            ->where('date_booked', $date->format('Y-m-d'))
            ->get();
    }

    public function getAllBookingsFinishingOnDate(Carbon $date)
    {
        $bookings = Booking::confirmed()
            ->where('date', $date->format('Y-m-d'))
            ->orWhere( function($q) use ($date) {
                $q->confirmed()->where('date', $date->subDay()->format('Y-m-d'));
            })
            ->get();

        $date->addDay();

        return $bookings->filter(function(Booking $b) use ($date) {
                if ($b->deleted != 0) return false;

                #dd($b->date);

                // if today, make sure its not finishing tomorrow
                if ($b->date == $date->format('d-m-Y')) return $b->start_time < $b->finish_time;

                // if tomorrow, make sure it started today
                return $b->start_time > $b->finish_time;
            });
    }
    
    public function getAllBookingsForUnpaidDeposits($deposit = NULL)
    {
        return Booking::confirmed()->where('deposit_paid', $deposit)
                ->orderBy('date', 'asc')->get(); 
    }
    
    public function getAllBookingsForUnpaidBalances($balance = NULL)
    {
        return \Booking::confirmed()
                ->where('balance_paid', $balance)
                ->orderBy('date', 'asc')->get();
    }
    
    public function getAllBookingsForTheFuture()
    {
        $bookings = Booking::with('client','package')->confirmed()
                ->where('date','>=', date('Y-m-d'))
                ->orderBy('date', 'asc')->get();
        return $bookings;
    }

    public function getAllBookingsAfterTheDate(Carbon $date)
    {
        $bookings = Booking::with('client','package')->confirmed()
                ->where('date','>=', $date->format('Y-m-d'))
                ->orderBy('date', 'asc')->get();
        return $bookings;
    }

    public function getAllBookingsBeforTheDate(Carbon $date)
    {
        $bookings = Booking::with('client','package')->confirmed()
                ->where('date','<=', $date->format('Y-m-d'))
                ->orderBy('date', 'asc')->get();
        return $bookings;
    }

    public function getAllBookingsBetweenDates(Carbon $from_date, Carbon $to_date)
    {
        $bookings = Booking::with('client','package')->confirmed()
                ->where('date','>=', $from_date->format('Y-m-d'))
                ->where('date','<=', $to_date->format('Y-m-d'))
                ->orderBy('date', 'asc')->get();
        return $bookings;
    }


}

?>
