<?php

use Carbon\Carbon;
use Helpers\DateTimeHelper;

class Booking extends Eloquent
{

    const STATUS_BOOKING = 'booking';
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMATION_REQUIRED = 'confirmation required';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bookings';
 
    protected $fillable = [
        'client_id',
        'package_id',
        'date',
        'date_booked',
        'start_time',
        'finish_time',
        'venue_name',
        'venue_address1',
        'venue_address2',
        'venue_address3',
        'venue_address4',
        'venue_postcode',
        'event_occasion',
        'occasion',
        'deposit_requested',
        'deposit_paid',
        'deposit_amount',
        'deposit_payment_method',
        'balance_requested',
        'balance_paid',
        'balance_amount',
        'balance_payment_method',
        'total_cost',
        'ref_no',
        'status',
        'setup_equipment_time',
        'staff',
        'notes',
        'groom_firstname',
        'groom_surname',
        'bride_firstname',
        'birthday_name',
        'birthday_age',
        'set_id'];

    protected $guarded = array('deleted', 'id');
    
    public static $datatypes = array(
        'date_booked' => 'date',
        'date' => 'date',
        'deposit_paid' => 'date',
        'balance_paid' => 'date',
    );
    
    public function client()
    {
        return $this->belongsTo('Client');
    }
    
    public function package()
    {
        return $this->belongsTo('Package');
    }

    public function extras()
    {
        return $this->belongsToMany('Extra');
    }

    public function followUp()
    {
        return $this->hasOne('FollowUp');
    }

    public function equipmentSet()
    {
        return $this->belongsTo('EquipmentSet', 'set_id');
    }
    
    public function setDateAttribute($value)
    {
        #echo "\n Setting date from $value";
        $value = DateTimeHelper::us_to_uk_date($value);
        if ($value != '') {
            $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['date'] = null;
        }
        #echo "\n Date set to ".$this->attributes['date'];exit;
    }
    
    public function setDateBookedAttribute($value)
    {
        #echo "\n Setting date booked from $value";exit;
        $value = DateTimeHelper::us_to_uk_date($value);
        if ($value != '') {
            $this->attributes['date_booked'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['date_booked'] = null;
        }
    }
    
    public function setDepositPaidAttribute($value)
    {
        $value = DateTimeHelper::us_to_uk_date($value);
        if ($value != '') {
            $this->attributes['deposit_paid'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['deposit_paid'] = null;
        }
    }
    
    public function setBalancePaidAttribute($value)
    {
        $value = DateTimeHelper::us_to_uk_date($value);
        if ($value != '') {
            $this->attributes['balance_paid'] = Carbon::createFromFormat('d-m-Y', $value)->toDateString();
        } else {
            $this->attributes['balance_paid'] = null;
        }
    }
    
    public function getDateAttribute($value)
    {
        return $value != '0000-00-00' ? DateTimeHelper::us_to_uk_date($value) : null;
    }

    public function getDateCoreAttribute($value)
    {
        return $value;
    }
    
    public function getDateBookedAttribute($value)
    {
        return $value != '0000-00-00' ? DateTimeHelper::us_to_uk_date($value) : null;
    }
    
    public function getDepositPaidAttribute($value)
    {
        return $value != '0000-00-00' ? DateTimeHelper::us_to_uk_date($value) : null;
    }
    
    public function getBalancePaidAttribute($value)
    {
        return $value != '0000-00-00' ? DateTimeHelper::us_to_uk_date($value) : null;
    }
    
    public function getTotalTravelCostAttribute($value)
    {
        $distance_in_minutes = \Helpers\PackageHelper::get_minutes_between_postcodes(Setting::getValueFromKey('home_postcode'), $this->attributes['venue_postcode']);
        return \Helpers\PackageHelper::get_travel_cost($this->package, $distance_in_minutes);
    }
    
    public function getDepositPaidUsAttribute()
    {
        return DateTimeHelper::uk_to_us_date($this->attributes['deposit_paid']);
    }
    
    public function getBalancePaidUsAttribute()
    {
        return DateTimeHelper::uk_to_us_date($this->attributes['balance_paid']);
    }
    
    public function getNotesWrappedAttribute($value)
    {
        if ($this->attributes['notes'] == null) {
            return '';
        }
        
        $value = str_replace(array("\r\n", "\r"), "\n", $this->attributes['notes']);
        $lines = explode("\n", $value);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if (! empty($line)) {
                $new_lines[] = trim($line);
            }
        }
        return implode($new_lines);
    }
    
    public function getTravelTimeAttribute($value)
    {
        #if coming from another booking ...
        
        #else from home
        return Helpers\PackageHelper::get_minutes_between_postcodes(Setting::getValueFromKey('home_postcode'), $this->venue_postcode);
    }
    
    public function getStartTimestampAttribute($value)
    {
        $date = new DateTime($this->attributes['date']);
        
        $parts = explode(':', $this->attributes['start_time']);
        
        $date->setTime($parts[0], $parts[1], $parts[2]);
        
        return $date->getTimestamp();
    }
    
    public function getFinishTimestampAttribute($value)
    {
        $date = new DateTime($this->attributes['date']);
        
        $parts = explode(':', $this->attributes['finish_time']);
        
        $date->setTime($parts[0], $parts[1], $parts[2]);
        
        return $date->getTimestamp();
    }

    public function getDateTimestampAttribute()
    {
        return Carbon::createFromFormat('d-m-Y', $this->date)->timestamp;
    }
    
    public static function further_details()
    {
        return array('wedding' => array ('bride_firstname' => 'Bride\'s First Name', 'groom_firstname' => 'Groom\'s first name', 'groom_surname' => 'Groom\'s surname'),
            'birthday' => array ('birthday_name' => 'Birthday person\'s name', 'birthday_age'=>'Birthday person\'s age'));
    }
    
    public static function occasions()
    {
        return array('wedding' => 'Wedding', 'birthday' => 'Birthday', 'other' => 'Other');
    }
    
    public function inPast()
    {
        return Carbon::today()->gt(Carbon::createFromFormat('Y-m-d', $this->attributes['date']));
    }

    public function getIsOpenAttribute()
    {
        return !$this->inPast() && $this->status == self::STATUS_BOOKING && $this->deleted == 0;
    }
    
    public function getInvoiceAmountAttribute()
    {
        return $this->total_cost;
    }
    
    public function getAmountOutstandingAttribute()
    {
        return max(0, number_format($this->total_cost - $this->deposit_amount - $this->balance_amount, 2));
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_BOOKING)->where('deleted', 0);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)->where('deleted', 0);
    }

    public function findDuplicate()
    {
        $duplicate = static::where('date', Carbon::createFromFormat('d-m-Y', $this->date)->toDateString())
            ->where('start_time', $this->start_time)
            ->where('finish_time', $this->finish_time)
            ->where('venue_name', $this->venue_name)
            ->where('venue_postcode', $this->venue_postcode)
            ->where('package_id', $this->package_id)
            ->where('setup_equipment_time', $this->setup_equipment_time)
            ->where('client_id', $this->client_id)
            ->where('ref_no', $this->ref_no)
            ->first();

        return $duplicate;
    }
}
