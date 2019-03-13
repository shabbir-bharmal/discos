<?php

namespace Core\Modules\Booking\Controller;

use Carbon\Carbon;
use Helpers\DateTimeHelper;
use Helpers\EmailHelper;

class BookingController extends \AdminController
{
    private $notifications = array(
        'create' => 'Booking has been added',
        'update' => 'Booking has been saved',
        'send' => 'The contract has been sent'
    );

    public function __construct()
    {
        parent::__construct();

        $this->data['tag'] = 'Booking management';
    }

    public function getIndex()
    {
        return $this->getSearch();
    }

    public function getSearch()
    {
        $this->data['bookings'] = \Booking::where('deleted', '=', 0)->get();
        $this->fetchRelationalData();
        $view = \Auth::user()->can('multiple.deletes') ? 'admin.bookings-search.multiple-deletes' : 'admin.bookings-search.default';
        return \View::make($view, $this->data);
    }

    public function getAdd()
    {
        $this->fetchRelationalData();

        $this->data['sets'] = \EquipmentSet::lists('name', 'id');

        return \View::make('admin.bookings-add', $this->data);
    }

    private function fetchRelationalData()
    {
        $this->data['clients'] = \Client::where('deleted', '=', 0)->get();

        $this->data['clientselection'] = \Client::get_selection(false);

        $packages = \Package::where('deleted', '=', 0)->get();

        foreach ($packages as $package) {
            $this->data['packages'][$package->id] = "$package->name, $package->day, $package->start_time - $package->finish_time";
        }

        $this->data['occasions'] = \Booking::occasions();

        $this->data['further_details'] = \Booking::further_details();

        $this->data['statuses'] = array(\Booking::STATUS_PENDING => 'Pending', \Booking::STATUS_BOOKING => 'Booking');

        $this->data['extras'] = \Extra::all()->lists('name', 'id');

        $this->data['sets'] = \EquipmentSet::lists('name', 'id');
    }

    public function getDuplicate($id)
    {
        $model = \Booking::find($id);

        $model->id = null;

        $new_model = new \Booking();

        $new_model->fill($model['attributes']);

        $new_model->save();

        return \Redirect::to('admin/bookings/search');
    }

    public function postAdd()
    {
        $input = \Input::except('_token', 'start_time', 'finish_time', 'client', 'extras');

        $input['start_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('start_time'));

        $input['finish_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('finish_time'));

        $extras = \Input::has('extras') ? \Input::get('extras') : false;

        // look for client
        $client = \Client::where('name', \Input::get('client'))->first();

        if (! $client) {
            // create a new client
            $client = new \Client;

            $client->name = \Input::get('client');

            $client->save();
        }

        $booking = new \Booking();

        $booking->fill($input);

        $booking->client_id = $client->id;

        if ($booking->save()) {
            if ($extras) {
                $booking->extras()->sync($extras);
            }

            $this->message = $this->notifications['create'];
        } else {
            $this->error = 'An error occurred';
        }

        return \Redirect::to('admin/bookings/edit/' . $booking->id)
            ->with('message', $this->message)
            ->with('error', $this->error);
    }

    public function edit($id)
    {
        $this->data['booking'] = \Booking::find($id);

        $this->fetchRelationalData();

        return \View::make('admin.bookings-edit', $this->data);
    }

    public function postEdit()
    {
        $input = \Input::except('_token', 'start_time', 'finish_time', 'extras');

        $input['start_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('start_time'));

        $input['finish_time'] = DateTimeHelper::timepicker_to_dbtime(\Input::get('finish_time'));

        $extras = \Input::has('extras') ? \Input::get('extras') : [];

        $input['status'] = $input['sendStatusChangeEmail'] == 0 ? 'booking-no-email' : $input['status'];

        if (\Input::has('delete')) {
            return $this->deleteBooking($input['id']);
        }

        $booking = \Booking::findOrFail($input['id']);

        if ($booking->update($input)) {
            // update extras
            $booking->extras()->sync($extras);

            // also store client in case it's changed
            $client = \Client::where('name', $input['client'])->first();

            if (!$client) {
                $this->error = 'That client could not be found';
            } else {
                $booking->client_id = $client->id;

                $booking->save();

                $this->message = $this->notifications['update'];
            }
        } else {
            $this->error = 'An error occurred';
        }

        return \Redirect::to('admin/bookings/edit/' . $input['id'])
                ->with('message', $this->message)
                ->with('error', $this->error);
    }

    public function delete($id)
    {
        return $this->deleteBooking($id);
    }

    public function postBooking()
    {
        $input = \Input::except('_token');

        if (\Input::has('delete')) {
            return $this->deleteBooking($input['id']);
        }

        if (\Input::has('id')) {
            \Booking::find($input['id'])->update($input);
        } else {
            \Booking::insert($input);
        }

        return \Redirect::to('admin/bookings');
    }

    public function deleteBooking($id)
    {
        if (intval($id) > 0) {
            $booking = \Booking::find($id);

            $booking->deleted = 1;

            $booking->save();
        }

        return \Redirect::to('admin/bookings');
    }

    public function getBooking($id)
    {
        if (intval($id) == 0) {
            return \Redirect::to('admin/bookings');
        }

        $data = \Booking::find($id);

        return \LResponse::json($data);
    }

    public function getCalenderBookings()
    {
        $bookings = array();

        $start = \LRequest::query('start');

        $end = \LRequest::query('end');

        $startDate = new \DateTime(date('Y-m-d', $start));

        $endDate = new \DateTime(date('Y-m-d', $end));

        $allBookings = \Booking::whereBetween('date', array($startDate, $endDate))->where('deleted', '=', '0')->get();



        foreach ($allBookings as $booking) {
            $date = new \DateTime($booking->date);

            $bk = new \FullCalendarEvent($date->getTimestamp());

            $bk->id = $booking->id;

            $bk->title = $booking->package->name . " " . $booking->start_time . "-" . $booking->finish_time;

            $bk->url = "/admin/bookings/edit/$booking->id";

            $bk->setStatus($booking->status);

            $bookings[] = $bk;
        }

        return \LResponse::json($bookings);
    }

    public function sendContract($booking_id)
    {
        $contract = \Contract::make($booking_id);

        if ($contract->send()) {
            $this->message = $this->notifications['send'];
        } else {
            $this->error = 'An error occured';
        }

        return \Redirect::to('admin/bookings/search')
            ->with('message', $this->message)
            ->with('error', $this->error);
    }

    public function getCalendar()
    {
        return \View::make('admin.bookings-calendar', $this->data);
    }

    public function deleteMultiple()
    {
        if (\Input::has('deletes') && \Auth::user()->can('multiple.deletes')) {
            foreach (\Input::get('deletes') as $id) {
                if (intval($id) > 0) {
                    $booking = \Booking::find($id);

                    $booking->deleted = 1;

                    $booking->save();
                }
            }
        }

        return \Redirect::to('admin/bookings');
    }

    public function deletePending()
    {
        $pendingBookings = \Booking::pending()->update([
            'deleted' => 1
        ]);

        return \LResponse::json([
            'msg' => 'Pending bookings deleted successfully',
            'location' => '/admin/bookings'
        ], 200);
    }

    public function deleteFollowUp($id)
    {
        $booking = \Booking::findOrFail($id);

        $booking->followUp->delete();

        return \Redirect::to('admin/bookings');
    }

    public function confirm($id)
    {
        $booking = \Booking::findOrFail($id);

        $booking->update(['status' => \Booking::STATUS_PENDING]);

        $email = $booking->package->emailtemplate;

        $email_template = EmailHelper::get_email_viewname($email);

        $date = new \DateTime(Carbon::createFromFormat('d-m-Y', $booking->date));

        $booking->timestamp = $date->getTimestamp();

        $this->email = array('link' => \Config::get('app.url') . 'booking/' . $booking->email_token);

        // below fix needed because substr won't save on live!
        $startpahar = (substr($booking->start_time, 0, -6)<13)?'AM':'PM';
        $finishpahar = (substr($booking->finish_time, 0, -6)<13)?'AM':'PM';
        $booking->start_time_formatted = (substr($booking->start_time, 0, -6)%12).":".substr($booking->start_time, 3, -3)." ".$startpahar;

        $booking->finish_time_formatted = (substr($booking->finish_time, 0, -6)%12).":".substr($booking->finish_time, 3, -3)." ".$finishpahar;

        # send email to client if this is a quotation
        $client = $booking->client;

        EmailHelper::sendQuotation($client->email, $email_template, compact('client', 'booking', 'email'), $client, $booking, $email);

        // Save email to follow ups table
        \FollowUp::create(['client_id' => $client->id, 'booking_id' => $booking->id]);

        return \View::make('frontend.booking-confirmed', $this->data);
    }

}
