<?php
namespace Disco\Controllers;
use Redirect;
use Input;
use Quote;
use Mail;
use RetrieveFromToken;
use Helpers\DateTimeHelper;
use BookingHandler;
class HomeController extends \BaseController
{
    function __construct()
    {
        parent::__construct();
        $this->data['tag'] = 'Disco bookings';
    }

    public function index()
    {
        $this->fetchRelationalData();
        return \View::make('frontend.homepage', $this->data);
    }

    private function fetchRelationalData()
    {
        foreach (\Package::select('name')->where('deleted', '=', '0')->distinct()->get() as $package) {
            $this->data['packages'][$package->name] = $package->name;
        }
    }

    public function getQuotation()
    {
		 
        $booker = (new BookingHandler(new Quote(), Input::except('_token')));

        $booker->go();

        if (!$booker->messages->isEmpty()) {
            try {
                return Redirect::back()->withInput(Input::all())->withErrors($booker->messages);
            } catch (Exception $e) {
                return Redirect::to('/')->withInput(Input::all())->withErrors($booker->messages);
            }
        }  

        $this->data['booking'] = $booker->get_booking();

        return \View::make('frontend.email-sent', $this->data);
    }

    public function booking($token)
    {
        $booker = (new BookingHandler(new RetrieveFromToken($token)));

        if (!$booking = $booker->get_booking()) return \Redirect::to('/');

        $this->data['token'] = $token;
        $this->data['booking'] = $booking;

        $booker->set_input_from_booking($booking)->go();

        if (!$booker->messages->isEmpty()) {
            unset($_SESSION['booking_id']);
            return Redirect::back()->withErrors($booker->messages);
        }

        if (isset($_SESSION['booking_id']) && $_SESSION['booking_id'] == $booking->id) {
            # correct user
            $this->data['further_details'] = \Booking::further_details();
            $this->data['occasions'] = \Booking::occasions();
            return \View::make('frontend.further-details', $this->data);
        } else {
            # need to validate yourself
            return \View::make('frontend.validate-yourself', $this->data);
        }
    }

    public function validate($token)
    {
        $this->data['token'] = $token;

        # check your name is right
        $booking = \Booking::where('email_token', $token)->where('status', \Booking::STATUS_PENDING)->first();

        if (count($booking) != 1) {
            return \Redirect::to('/');
        }

        if ($booking->date == \Input::get('date')) {
            $_SESSION['booking_id'] = $booking->id;
            return \Redirect::to('booking/' . $token);
        }

        $errors = new \Illuminate\Support\MessageBag();
        $errors->add('invalid', "This information does not match our records.");

        $this->data['errors'] = $errors;

        return \View::make('frontend.validate-yourself', $this->data);
    }

    public function postBooking()
    {
        $booker = new BookingHandler(new \Confirm($_SESSION['booking_id']), \Input::except('_token'));

        $booker->go();

        if (!$booker->messages->isEmpty()) {
            if ($booker->messages->has('availability')) unset($_SESSION['booking_id']);
            return Redirect::back()->withInput(Input::all())->withErrors($booker->messages);
        }

        return \Redirect::to('complete');
    }

    public function complete()
    {
        if (isset($_SESSION['booking_id'])) {

            $this->data['booking'] = \Booking::find($_SESSION['booking_id']);
            unset($_SESSION['booking_id']);
            return \View::make('frontend.complete', $this->data);
        }

        return \Redirect::to('/');
    }
     public function auto_quote()
    {
		$data="";
		if(isset($_POST)){
			foreach($_POST as $k=>$v){
				$data.="<strong>".$k." </strong>:". $v."<br>";
				}
		}
		$headers="MIME-Version: 1.0" . "\r\n";
		$headers.="Content-type: text/html; charset=utf-8" . "\r\n";
		mail('AutoQuote@djnickburrett.com','AutoQuote',$data,$headers);
        $_token = csrf_token();
        $input = Input::except('_token');

        $input['date'] = $_POST['Day'] . "-" . $_POST['Month'] . "-" . $_POST['Year'];
        $input['start_time'] = $_POST['Start'];
        $input['finish_time'] = ($_POST['End'] == "Midnight") ? '00:00' : $_POST['End'];
        $input['venue_name'] = !empty($_POST['Venue']) ? $_POST['Venue'] : "empty";
        $input['venue_postcode'] = !empty($_POST['Postcode']) ? $_POST['Postcode'] : '00';
		
		if(strlen($input['venue_postcode'])<=4){
			
			$input['venue_postcode']=!empty($_POST['Town'])?$_POST['Town']:"00";
			}
            $input['occasion'] = '';
        if (isset($_POST['Event'])) {
			$event=$_POST['Event'];
			$split_str = explode(' ', $event, 4);
			$result="";
			if(array_key_exists(0,$split_str)){
			$result .=$split_str[0]." ";}
			if(array_key_exists(1,$split_str)){
			$result .=$split_str[1]." ";}
			if(array_key_exists(2,$split_str)){
			$result .=$split_str[2];}
		    if($result=="Birthday - Boy" || $result=="Birthday - Girl" || $event=="Birthday (6yrs or under)" || $event=="Birthday (7-11 years)"){			 
			 $package_event="Kids Party DJ";
			}
		    elseif($result=="Birthday (12-15 years)" || $result=="School Disco - Primary"){			 
			 $package_event="Kids Party DJ";
			}
            else if($event=="Wedding or Civil Partnership"){			
			 $package_event="Wedding DJ and Disco Hire";
			}else{
			 $package_event="DJ and Disco Hire";
			}
			$input['package_name']=$package_event;
            $input['occasion'] = $_POST['Event'];
		}


         
        $input['name'] = !empty($_POST['Client']) ? $_POST['Client'] : "empty";
        $input['email'] = $_POST['Email'];
        $input['telephone'] = !empty($_POST['Phone']) ? $_POST['Phone'] : "0000";
        $input['mobile'] = !empty($_POST['Phone']) ? $_POST['Phone'] : "0000";
        $input['heard_about'] = !empty($_POST['Source']) ? $_POST['Source'] : "empty";
        $input['ref_no'] = !empty($_POST['Source']) ? $_POST['Source'] : "empty";
        $input['_token'] = $_token;

        Input::merge(array('date' => $input['date']));
        Input::merge(array('start_time' => $input['start_time']));
        Input::merge(array('finish_time' => $input['finish_time']));
        Input::merge(array('venue_name' => $input['venue_name']));
        Input::merge(array('venue_postcode' => $input['venue_postcode']));
        Input::merge(array('package_name' => $input['package_name']));
        Input::merge(array('name' => $input['name']));
        Input::merge(array('email' => $input['email']));
        Input::merge(array('telephone' => $input['telephone']));
        Input::merge(array('mobile' => $input['mobile']));
        Input::merge(array('heard_about' => $input['heard_about']));
        Input::merge(array('ref_no' => $input['ref_no']));
        Input::merge(array('_token' => $input['_token']));
        $booker = (new BookingHandler(new Quote(), Input::except('_token')));
        $booker->go();

        if (!$booker->messages->isEmpty()) {
            $msg = $booker->messages->toArray();
            foreach ($msg as $mg) {
                foreach ($mg as $k => $v) {
                    echo $v;
                }
				exit;
            }
            return Redirect::back()->withInput(Input::all())->withErrors($booker->messages);
        }

        $this->data['booking'] = $booker->get_booking();
        return \View::make('frontend.email-sent', $this->data);
    }

}
