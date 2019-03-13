<?php namespace Core\Modules\Booking\Controller;

use Helpers\DateTimeHelper;

class ExtraController extends \AdminController {

    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Additional Extras';
    }


    public function getIndex()
    {
        return $this->getAll();
    }

    public function getAll()
    {
        $this->data['extras'] = \Extra::all();
        $this->get_relational_data();
        return \View::make('admin.extras', $this->data);
    }

    private function get_relational_data()
    {
    }

    public function postExtra()
    {
        $input = \Input::except('_token','start_time', 'finish_time');

        if(\Input::has('delete')) {
            return $this->deleteExtra($input['id']);
        }

        if(\Input::has('id')) {
            \Extra::find($input['id'])->update($input);
        } else {
            \Extra::insert($input);
        }

        return \Redirect::to('admin/extras');
    }

    public function deleteExtra($id)
    {
        if(intval($id) > 0) {
            $extra = \Extra::findorFail($id);

            // todo: check for usage?
            $extra->delete();
        }

        return \Redirect::to('admin/extras');
    }

    public function getExtra($id)
    {
        if(intval($id) == 0) {
            return \Redirect::to('admin/extras');
        }

        $data = \Extra::find($id);

        return json_encode($data);
    }

}
