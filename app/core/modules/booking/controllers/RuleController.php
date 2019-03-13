<?php namespace Core\Modules\Booking\Controller;

use Helpers\DateTimeHelper;

class RuleController extends \AdminController {
    
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
        $this->data['rules'] = \Rule::where('deleted','=','0')->get();
        $this->get_relational_data();
        return \View::make('admin.package_rules', $this->data);
    }
    
    private function get_relational_data()
    {
        $packages = \Package::where('deleted', '=', 0)->get();
        $this->data['packages'][0] = "Set as unavailable";
        foreach ($packages as $package) {
            $this->data['packages'][$package->id] = "$package->name, $package->day, $package->start_time - $package->finish_time";
        }
    }

    public function postIndex()
    {       
        $input = \Input::except('_token');
        
        if(\Input::has('delete')) {
            return $this->deleteRule($input['id']);
        }
        
        if(\Input::has('id')) {
            $rule = \Rule::find($input['id'])->update($input);
        } else {
            $rule = new \Rule;
            $rule->name = $input['name'];
            $rule->date_from = $input['date_from'];
            $rule->date_to = $input['date_to'];
            $rule->package_id = $input['package_id'];
            $rule->save();
        }
        
        return \Redirect::to('admin/packages/rules');  
        
    }
    
    public function deleteRule($id)
    {
        if(intval($id) > 0) {
            $rule = \Rule::find($id);
            $rule->deleted = 1;
            $rule->save();
        }
        
        return \Redirect::to('admin/packages/rules');    
        
    }    
    
    public function getRule($id)
    {
        if(intval($id) == 0) {
            return \Redirect::to('admin/packages/rules');
        }
        
        $data = \Rule::find($id);
        #$data['start_time'] = DateTimeHelper::dbtime_to_timepicker($data['start_time']);
        #$data['finish_time'] = DateTimeHelper::dbtime_to_timepicker($data['finish_time']);
        
        return json_encode($data);
    }

}
