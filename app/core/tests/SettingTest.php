<?php

class SettingTest extends TestCase {
    
    
    public function testSettingFound()
    {   
        $setting = new Setting();
        $setting->key = 'admin_email';
        $setting->value = 'nick@djnickburrett.com';
        $setting->notes = 'Bcc email for quotation';
        $setting->save();
        
        $admin_email = Setting::where('key', '=', 'admin_email')->get()->first();
        $bcc_email = ($admin_email) ? $admin_email->value : false;
        
        $this->assertEquals($setting->value, $bcc_email);
        
    }
    
    public function testSettingNotFound()
    {   
        
        $admin_email = Setting::where('key', '=', 'admin_email')->get()->first();
        $bcc_email = ($admin_email) ? $admin_email->value : "other";
        
        $this->assertEquals('other', $bcc_email);
        
    }


}
