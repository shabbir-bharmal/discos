<?php


class EmailOffer extends Eloquent
{
    
    const TYPE_PENDING = 'pending';
    const TYPE_OFFER = 'offer';

    const TYPE_CONFIRMATION_REQUIRED = 'confirmation required';
    const TYPE_BOOKING = 'booking';
    
    const RECIPIENT_CLIENT = 'client';
    
    protected $table = 'email_offers';
 
    protected $fillable = array('template_id', 'title', 'booking_type', 'booking_status', 'from_date',
        'end_date', 'date', 'run_hour', 'status');
    
    protected $guarded = array('id', 'deleted');
    
    public static function all($columns = array())
    {
        return \EmailOffer::where('deleted', '=', 0)->get();
    }
    
    
    public static function schedules()
    {
        return self::where('deleted', '=', 0)->get();
        //return self::where('id', '=', 2)->get();
    }

    public function packages()
    {
        return $this->hasMany('Package', 'email_template_id', 'id');
    }
    
    public function getTemplatePathAttribute()
    {
        return dirname(__FILE__)."/../views/templates/emails/$this->view" . ".blade.php";
    }
    
    public function getHtmlAttribute()
    {
        // get html from file
        $value = file_get_contents($this->template_path);
        return str_replace('&#39;', "'", $value);
    }
    
    public function setHtmlAttribute($value)
    {
        file_put_contents($this->template_path, html_entity_decode($value));
    }
    
    
    public static function get_selection($emptyitem_text = 'Select an email template', $excludes = array(), $collection = false)
    {
        $selection = array(0 => $emptyitem_text);
        
        $email_templates = $collection ?: self::all();
        
        foreach ($email_templates as $email_template) {
            if (in_array($email_template->id, $excludes)) {
                continue;
            }
            $selection[$email_template->id] = $email_template->name;
        }
        
        return $selection;
    }
    
    
    
    public static function get_booking_type_selection()
    {
        return array(self::TYPE_PENDING => 'Pending',
            self::TYPE_CONFIRMATION_REQUIRED => 'Confirmation Required',
            self::TYPE_BOOKING => 'Booking'
        );
    }


    public static function get_type_selection()
    {
        return array(self::TYPE_QUOTATION => 'Quotation email',
            self::TYPE_REGULAR => 'Scheduled email',
            self::TYPE_EVENT_PARTY => 'Send whilst at the party',
            self::TYPE_EVENT_PARTY_FINISHED => 'Send when party has finished',
            self::TYPE_EVENT_STATUS_CHANGED => 'Send when booking status changes',
            self::TYPE_FOLLOW_UP => 'Follow up email',
        );
    }
    
   
    public static function get_recipient_selection()
    {
        $array = array(self::RECIPIENT_CLIENT => 'The client');
        
        foreach (Setting::getAllDistinctEmails() as $email) {
            $array[$email->value] = $email->value;
        }
        
        return $array;
    }
    
    
    public static function get_data_selection($emptyitem_text = 'Current booking')
    {
        $selection = array('current' => $emptyitem_text);

        $selection['unpaid'] = 'Unpaid deposits and balances';
        $selection['future_bookings'] = 'All future bookings';
            
        return $selection;
    }
}
