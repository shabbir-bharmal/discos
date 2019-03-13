<?php

class Extra extends Eloquent {

    public $fillable = [
        'name',
        'description'
    ];

    public function bookings()
    {
        return $this->belongsToMany('Booking');
    }

    public static function byName($name)
    {
        return self::where('name', $name)->first();
    }
}