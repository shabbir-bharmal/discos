<?php

use \Helpers\DateTimeHelper;

class Package extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packages';
    protected $fillable = array('name', 'day','start_time', 'finish_time', 'min_price', 'max_price', 'hours_inc', 'overtime_cost',
        'overtime_interval', 'free_travel', 'travel_cost', 'travel_interval', 'email_template_id', 'setup_time', 'deposit', 'due_date', 'description', 'valid_from', 'valid_to');
    protected $guarded = array('deleted', 'id');
    
    public function booking()
    {
        return $this->hasMany('Booking');
    }
    
    public function emailtemplate()
    {
        return $this->belongsTo('EmailTemplate', 'email_template_id');
    }
    
    public function getFullDescription()
    {
        return $this->name . " " . DateTimeHelper::dbtime_to_timepicker($this->start_time) . "-" . DateTimeHelper::dbtime_to_timepicker($this->finish_time);
    }

    public function equipmentSets()
    {
        return $this->belongsToMany('EquipmentSet');
    }
}
