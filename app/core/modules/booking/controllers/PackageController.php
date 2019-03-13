<?php namespace Core\Modules\Booking\Controller;

use Helpers\DateTimeHelper;

class PackageController extends \AdminController {
    
    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Package management';
    }

    
    public function getIndex()
    {
        return $this->getAll();
    }
    
    public function getAll()
    {
        $this->data['packages'] = \Package::where('deleted','=',0)->get();
        $this->get_relational_data();
        return \View::make('admin.packages', $this->data);
    }
    
    private function get_relational_data()
    {
        $templates = array();
        foreach(\EmailTemplate::all() as $template)
        {
            $templates[$template->id] = $template->name;
        }
        
        $this->data['email_templates'] = $templates;
    }

    public function postPackage()
    {        
        $input = \Input::except('_token','start_time', 'finish_time');
        $input['start_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('start_time'));
        $input['finish_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('finish_time'));
        
        if(\Input::has('delete')) {
            return $this->deletePackage($input['id']);
        }
        
        if(\Input::has('id')) {
            \Package::find($input['id'])->update($input);
        } else {
            \Package::insert($input);
        }
        
        return \Redirect::to('admin/packages');
    }
    
    public function deletePackage($id)
    {
        if(intval($id) > 0) {
            $package = \Package::find($id);
            $package->deleted = 1;
            $package->save();
        }
        
        return \Redirect::to('admin/packages');        
    }    
    
    public function getPackage($id)
    {
        if(intval($id) == 0) {
            return \Redirect::to('admin/packages');
        }
        
        $data = \Package::find($id);
        $data['start_time'] = DateTimeHelper::dbtime_to_timepicker($data['start_time']);
        $data['finish_time'] = DateTimeHelper::dbtime_to_timepicker($data['finish_time']);
        
        return json_encode($data);
    }

}
