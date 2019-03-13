<?php

namespace Core\Modules\Email\Controller;

class EmailController extends \BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Email management';
    }
    
    function getIndex()
    {
        $this->data['emails'] = \EmailTemplate::all();
        return \View::make('admin.emails', $this->data);
    }
    
    function getAll()
    {
        return \Email::all();
    }
    
    function getTemplate($id)
    {
        $template = \EmailTemplate::find($id);
        $response = array('html' => $template->html);
        return json_encode($response);
    }
    
    function getEdit($id)
    {
        $this->data['email'] = \EmailTemplate::find($id);
        $this->get_relational_data();
        return \View::make('admin.emails-edit', $this->data);
    }
    
    private function get_relational_data($path = null)
    {            
        $path = ($path != null) ? $path : __DIR__ . '/../views/templates/emails/';
        $templates = scandir($path);
        $all_templates = array();

        foreach ($templates as $template) {

            if ($template == '.' || $template == '..')
                continue;
            
            $view = str_replace(".blade.php", "", $template);            
            $all_templates[$view] = $view;
        }
        
        $this->data['views'] = $all_templates;    
    }
        
    public function getAdd()
    {
        $this->get_relational_data();
        return \View::make('admin.emails-add', $this->data);
    }
    
    public function postAdd()
    {
        $input = \Input::except('_token');
        $id = \EmailTemplate::insert($input);

        return \Redirect::to('admin/emails');
    }
    
    function postEdit()
    {
        $input = \Input::except('_token');
        
        #dd($input);
        
        \EmailTemplate::find($input['id'])->update($input);        
                
        if (\Input::has('delete')) {
            return $this->deleteEmail($input['id']);
        } 
        
        return \Redirect::to('admin/emails/edit/' . $input['id']);
    }
    
    function getHtml(\EmailTemplate $email)
    {
        return file_get_contents($email->html);
    }
    
    public function deleteEmail($id)
    {
        if (intval($id) > 0) {
            $booking = \EmailTemplate::find($id);
            $booking->deleted = 1;
            $booking->save();
        }

        return \Redirect::to('admin/emails');
    }
}
