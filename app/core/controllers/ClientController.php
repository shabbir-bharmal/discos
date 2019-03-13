<?php

class ClientController extends AdminController {
    
    private $notifications = array(
        'create' => 'Client has been added',
        'update' => 'Client has been saved'
    );

    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Client management';
    }
    
    public function getIndex()
    {
        return $this->getSearch();
    }

    public function getSearch()
    {
        $this->data['clients'] = \Client::where('deleted', '=', 0)->get();
        $view = Auth::user()->can('multiple.deletes') ? 'admin.clients-search.multiple-deletes' : 'admin.clients-search.default';
        return \View::make($view, $this->data);
    }
    
    public function getAll()
    {
        $this->data['clients'] = Client::where('deleted','=',0)->get();
        return View::make('admin.clients', $this->data);
    }

    public function getAdd()
    {
        return \View::make('admin.clients-add', $this->data);
    }

    public function postClient()
    {        
        $input = Input::except('_token');
        
        if(Input::has('delete')) {
            return $this->deleteClient($input['id']);
        }
        
        if(Input::has('id')) {
            $client = Client::find($input['id']);
            if ($client->update($input)) {
                $this->message = $this->notifications['update']; 
            } else {
                $this->error = 'An error occured';
                // todo: record the msg (or do this in a filter?)
            }
            
        } else {

            if ($client = Client::storeClient($input)) {
                $this->message = $this->notifications['create'];
            } else {
                $this->error = 'An error occurred';
            }
        }
        
        return Redirect::to('admin/clients/edit/'.$client->id)
                ->with('message', $this->message)
                ->with('error', $this->error);
    }

    public function getDelete($id)
    {
        return $this->deleteClient($id);
    }
    
    public function deleteClient($id)
    {
        if(intval($id) > 0) {
            $client = Client::find($id);
            $client->deleted = 1;
            $client->save();
        }
        
        return Redirect::to('admin/clients');        
    }    
    
    public function getClient($id)
    {
        if(intval($id) == 0) {
            return Redirect::to('admin/clients');
        }
        
        $data = Client::find($id);
        
        return json_encode($data);
    }

    public function getEdit($id)
    {
        $this->data['client'] = \Client::find($id);
        return \View::make('admin.clients-edit', $this->data);
    }

    public function deleteMultiple()
    {
        if (\Input::has('deletes') && \Auth::user()->can('multiple.deletes')) {
            foreach(\Input::get('deletes') as $id) {

                if (intval($id) > 0) {
                    $client = Client::find($id);
                    $client->deleted = 1;
                    $client->save();
                }
            }
        }

        return \Redirect::to('admin/clients');
    }

    public function deletePending()
    {
//        $pendingBookings = \Booking::pending()->update([
//            'deleted' => 1
//        ]);

        $clients = \Client::notDeleted()->select(['id'])->get();

        foreach ($clients as $client) {
            // Check whether client has any bookings
            $bookings = $client->bookings()->select(['status'])->get();

            foreach ($bookings as $booking) {
                if ($booking->status == 'booking') {
                    // Client is active, move to next one
                    continue 2;
                }
            }

            // If we're here, client is not active. Delete
            $client->deleted = 1;
            $client->save();
        }

        return \LResponse::json([
            'msg' => 'Pending clients deleted successfully',
            'location' => '/admin/clients'
        ], 200);
    }

}
