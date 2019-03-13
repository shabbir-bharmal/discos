<?php

class EquipmentSet extends Eloquent
{

    public $fillable = [
        'name',
        'description'
    ];

    public function packages()
    {
        return $this->belongsToMany('Package');
    }

    public function bookings()
    {
        return $this->hasMany('Booking', 'set_id');
    }
}
