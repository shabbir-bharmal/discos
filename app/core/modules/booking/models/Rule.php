<?php
use Carbon\Carbon;
use Helpers\DateTimeHelper;

class Rule extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rules';
    protected $fillable = array('name', 'date_from','date_to', 'package_id');
    protected $guarded = array('deleted', 'id');
    
    public function package()
    {
        return $this->belongsTo('Package');
    }
    
    public function setDateFromAttribute($value)
    {
        #echo "\n Setting date from $value";
        $value = DateTimeHelper::us_to_uk_date($value);
        if($value != '') {
            $this->attributes['date_from'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['date_from'] = null;
        }
        #echo "\n Date set to ".$this->attributes['date'];exit;
    }
    
    public function setDateToAttribute($value)
    {
        #echo "\n Setting date from $value";
        $value = DateTimeHelper::us_to_uk_date($value);
        if($value != '') {
            $this->attributes['date_to'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['date_to'] = null;
        }
        #echo "\n Date set to ".$this->attributes['date'];exit;
    }
}
