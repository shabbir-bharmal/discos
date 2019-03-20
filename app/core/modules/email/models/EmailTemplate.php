<?php


class EmailTemplate extends Eloquent
{
    
    const TYPE_REGULAR = 'regular';
    const TYPE_OFFER = 'offer';
    const TYPE_EVENT_PARTY = 'party';
    const TYPE_EVENT_PARTY_FINISHED = 'party.finished';
    const TYPE_EVENT_STATUS_CHANGED = 'booking.status_changed';
    const TYPE_QUOTATION = 'quotation';
    const TYPE_FOLLOW_UP = 'followup';
    
    const RECIPIENT_CLIENT = 'client';
    
    const REGULARITY_DAILY = 'daily';
    const REGULARITY_WEEKLY = 'weekly';
    const REGULARITY_MONTHLY = 'monthly';

    const WHEN_WEEK_BEFORE = 'week_before';
    const WHEN_WEEK_AFTER = 'week_after';
    const WHEN_MONTH_BEFORE_ANNIVERSARY = 'month_before_anniversary';
    
    protected $table = 'emailtemplates';
 
    protected $fillable = array('name', 'subject', 'email_from', 'name_from', 'view',
        'regularity', 'html', 'type', 'recipient', 'filter', 'cc', 'reply_to', 'data', 'scheduled',
        'day_of_week', 'day_of_month', 'execution_hour', 'packages');
    
    protected $guarded = array('id', 'deleted');
    
    public static function all($columns = array())
    {
        return \EmailTemplate::where('deleted', '=', 0)->get();
    }
    
    public static function schedules()
    {
        return self::where('deleted', '=', 0)->where('type', '=', self::TYPE_REGULAR)->orWhere('type', '=', self::TYPE_FOLLOW_UP)->get();
        //return self::where('id', '=', 2)->get();
    }
    
    public function packages()
    {
        return $this->hasMany('Package', 'email_template_id', 'id');
    }
    
    public function getPackagesAttribute()
    {
        return unserialize($this->attributes['packages']) ?: array();
    }
    
    public function setPackagesAttribute($value)
    {
        $this->attributes['packages'] = serialize($value);
    }
    
    public function getPackagesArray()
    {
        return json_decode(unserialize($this->attributes['packages']), true);
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
    
    public function setPackages(array $values)
    {
        $this->attributes['package'] = json_encode($values);
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
    
    public static function get_schedule_selection($emptyitem_text = 'Select an email template', $excludes = array())
    {
        $regular_emails = EmailTemplate::where('type', '=', self::TYPE_REGULAR)->orWhere('type', '=', self::TYPE_FOLLOW_UP)->get();
        return self::get_selection($emptyitem_text, $excludes, $regular_emails);
    }

    public static function get_offer_selection($emptyitem_text = 'Select an email template', $excludes = array())
    {
        $offer_emails = EmailTemplate::where('type', '=', self::TYPE_OFFER)->get();
        return self::get_selection($emptyitem_text, $excludes, $offer_emails);
    }
    
    public static function get_type_selection()
    {
        return array(self::TYPE_QUOTATION => 'Quotation email',
            self::TYPE_REGULAR => 'Scheduled email',
            self::TYPE_OFFER => 'Offer email',
            self::TYPE_EVENT_PARTY => 'Send whilst at the party',
            self::TYPE_EVENT_PARTY_FINISHED => 'Send when party has finished',
            self::TYPE_EVENT_STATUS_CHANGED => 'Send when booking status changes',
            self::TYPE_FOLLOW_UP => 'Follow up email',
        );
    }
    
    public static function get_regularity_selection()
    {
        return array(self::REGULARITY_DAILY => 'Daily',
                self::REGULARITY_WEEKLY => 'Weekly',
                self::REGULARITY_MONTHLY => 'Monthly');
    }
    
    public static function get_recipient_selection()
    {
        $array = array(self::RECIPIENT_CLIENT => 'The client');
        
        foreach (Setting::getAllDistinctEmails() as $email) {
            $array[$email->value] = $email->value;
        }
        
        return $array;
    }
    
    public static function get_filter_selection($emptyitem_text = 'On the event')
    {
        $selection = array('event' => $emptyitem_text);

        $selection[self::WHEN_WEEK_BEFORE] = 'A week before the event';
        $selection[self::WHEN_WEEK_AFTER] = 'A week after the event';
        $selection[self::WHEN_MONTH_BEFORE_ANNIVERSARY] = 'A month before the anniversary';

        for ($i=1; $i <= 31; $i++) {
            $selection[$i] = $i . ' Days after quotation';
        }
            
        return $selection;
    }
    
    public static function get_data_selection($emptyitem_text = 'Current booking')
    {
        $selection = array('current' => $emptyitem_text);

        $selection['unpaid'] = 'Unpaid deposits and balances';
        $selection['future_bookings'] = 'All future bookings';
            
        return $selection;
    }
}
