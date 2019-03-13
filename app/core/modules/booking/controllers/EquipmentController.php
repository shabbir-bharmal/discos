<?php namespace Core\Modules\Booking\Controller;

use Helpers\DateTimeHelper;

class EquipmentController extends \AdminController {
    
    public function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Equipment management';
    }

    
    public function getIndex()
    {
        return $this->getAll();
    }
    
    public function getAll()
    {
        $this->data['sets'] = \EquipmentSet::all();
        // $this->get_relational_data();
        return \View::make('admin.equipment_sets', $this->data);
    }

    public function postSet()
    {
        $input = \Input::except('_token', 'packages');
        
        if (\Input::has('delete')) {
            return $this->deleteSet($input['id']);
        }
        
        if (\Input::has('id')) {
            $set = \EquipmentSet::find($input['id']);
            $set->update($input);
        } else {
            $set = \EquipmentSet::create($input);
        }
        //print_r($set); die;
        $set->packages()->sync(\Input::get('packages'));
        
        return \Redirect::to('admin/sets');
    }
    
    public function deleteSet($id)
    {
        if (intval($id) > 0) {
            $set = \EquipmentSet::find($id);
            $set->packages()->sync([]);
            $set->delete();
        }
        
        return \Redirect::to('admin/sets');
    }
    
    public function getSet($id)
    {
        if (intval($id) == 0) {
            return \Redirect::to('admin/sets');
        }
        
        $data = \EquipmentSet::find($id);
        $data['packages'] = $data->packages()->lists('id', 'id');
        
        return json_encode($data);
    }
}
