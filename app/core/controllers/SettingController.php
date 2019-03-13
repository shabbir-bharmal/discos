<?php

class SettingController extends AdminController {

    
    public function getIndex()
    {
        return $this->getAll();
    }
    
    public function getAll()
    {
        $this->data['settings'] = Setting::all();
        return View::make('admin.settings', $this->data);
    }

    public function postSetting()
    {        
        $input = Input::except('_token');
        
        if(Input::has('delete')) {
            return $this->deleteSetting($input['id']);
        }
        
        if(Input::has('id')) {
            Setting::find($input['id'])->update($input);
        } else {
            Setting::insert($input);
        }
        
        return Redirect::to('admin/settings');
    }  
    
    public function getSetting($id)
    {
        if(intval($id) == 0) {
            return Redirect::to('admin/settings');
        }
        
        $data = Setting::find($id);
        
        return json_encode($data);
    }

    public function deleteSetting($id)
    {
        Setting::findOrFail($id)->delete();

        return Redirect::to('admin/settings');
    }

}
