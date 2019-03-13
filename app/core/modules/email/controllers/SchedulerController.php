<?php

namespace Core\Modules\Email\Controller;

class SchedulerController extends \BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Email scheduler';
    }
    
    function getIndex()
    {
        $this->data['schedules'] = \EmailTemplate::schedules();
        $this->get_relational_data();
        return \View::make('admin.schedule', $this->data);
    }
    
    function getAll()
    {
        return \EmailTemplate::schedules();
    }
    
    function getEdit($id)
    {
        $this->data['schedule'] = \EmailTemplate::find($id);
        $this->get_relational_data();
        return \View::make('admin.schedule-edit', $this->data);
    }
    
    private function get_relational_data()
    {
        $this->data['delete_available'] = false;
        $this->data['add_available'] = false;
        $this->data['clear_available'] = false;
    }
    
    public function getSchedule($id)
    {
        $schedule = \EmailTemplate::find($id);
        $response = array(
            'id' => $id,
            'scheduled' => $schedule->scheduled,
            'regularity' => $schedule->regularity,
            'day_of_week' => $schedule->day_of_week,
            'day_of_month' => $schedule->day_of_month,
            'execution_hour' => $schedule->execution_hour);
        
        return json_encode($response);
    }
    
    function postEdit()
    {
        $input = \Input::except('_token');   
                
        if (\Input::has('delete')) {
            return $this->deleteSchedule($input['id']);
        } 
        
        if(\Input::has('id')) {
            $input['scheduled'] = isset($input['scheduled']) ? 1 : 0;
            \EmailTemplate::find($input['id'])->update($input);
        }
        
        return \Redirect::to('admin/schedules');
    }
}
