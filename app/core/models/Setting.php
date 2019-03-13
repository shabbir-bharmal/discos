<?php


class Setting extends Eloquent  {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settings';
    
    protected $fillable = array('key', 'value', 'notes');
    
    protected $guarded = array('id');


    public static function site($columns = null)
    {
        $all = parent::all($columns);
        
        $settings = new stdClass;        
        
        foreach($all->all() as $setting) {
            $key = $setting->key;
            $settings->$key = $setting->value;
        }
        
        return $settings;
    }
    
    public static function getAllDistinctEmails()
    {
        return Setting::where('key', 'like', '%_email')->select('value')->distinct()->get();
    }
    
    public static function getValueFromKey($key)
    {
        $settings = self::where('key', $key)->get();
        return $settings->count() == 0 ? null : $settings->first()->value;
    }

    public static function add($key, $value)
    {
        $setting = self::where('key', $key)->first() ?: new Setting;
        $setting->key = $key;
        $setting->value = $value;
        return $setting->save();
    }
    
}
