<?php

class ExportController extends AdminController {
    

    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Export facility';
    }
    
    public function getIndex()
    {
        $this->data['fields'] = Export::getAllModelAttributes();
        return View::make('admin.export', $this->data);
    }
    
    /*
     * export from query across tables
     */
    public function postIndex()
    {
        $collection = Export::getCollectionFromPostFiltersForExport(Input::get('filter'));
        
        $fields = Export::cleanPostFieldsForExport(Input::except('_token', 'filter', 'mark_as_done', 'only_fresh_records'));
        
        $export = new Export($collection, new DownloadOutput());
        return $export->run($fields, Input::get('mark_as_done'), Input::get('only_fresh_records'));
    }
        
    private function getModelClassName($string)
    {
         return substr(ucfirst($string), 0, -1);
    }

}
