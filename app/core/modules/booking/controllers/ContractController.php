<?php namespace Core\Modules\Booking\Controller;

class ContractController extends \AdminController {


    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Contract management';
    }

    
    public function getIndex()
    {
        return $this->getAll();
    }
    
    public function getAll()
    {
        $this->data['contracts'] = \Contract::where('deleted','=',0)->get();
        return \View::make('admin.contracts', $this->data);
    }

    public function postContract()
    {
        //todo: check token is valid
        //todo: check valid values
        
        $input = Input::except('_token');
        
        if(Input::has('delete')) {
            return $this->deleteContract($input['id']);
        }
        
        if(Input::has('id')) {
            Contract::find($input['id'])->update($input);
        } else {
            Contract::insert($input);
        }
        
        return Redirect::to('admin/contracts')
            ->with('message', $this->message)
            ->with('error', $this->error);
    }
    
    public function deleteContract($id)
    {
        if(intval($id) > 0) {
            $contract = Contract::find($id);
            $contract->deleted = 1;
            $contract->save();
        }
        
        return Redirect::to('admin/contracts');        
    }    
    
    public function getContract($id)
    {
        if(intval($id) == 0) {
            return Redirect::to('admin/contracts');
        }
        
        $data = Contract::find($id);
        
        return json_encode($data);
    }

}
