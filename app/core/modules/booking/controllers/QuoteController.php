<?php

namespace Core\Modules\Booking\Controller;

use AdminController;
use Booking;
use BookingHandler;
use Carbon\Carbon;
use Client;
use Config;
use DateTime;
use Helpers\DateTimeHelper;
use Helpers\EmailHelper;
use Input;
use InternalQuote;
use Package;
use Redirect;
use ScheduledEmails;
use StatusChangedEmails;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Error;
use Stripe\Stripe;
use Session;
use View;

class QuoteController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['tag'] = 'Quote management';
    }

    public function getIndex()
    {
        $this->data['packages'] = Package::whereDeleted(0)->distinct()->lists('name', 'name');

        return View::make('admin.quote.index', $this->data);
    }

    public function getPackageDetails($name)
    {
       $package = Package::whereDeleted(0)->where("name", $name)->distinct()->first();

        return $package;
    }

    public function postStep1()
    {
        $booker = (new BookingHandler(new InternalQuote(), Input::except('_token')));

        $booker->go();

        if (!$booker->messages->isEmpty()) {
            if ($booker->messages->has('availability')) {
                $events = Booking::whereDeleted(0)->where('date', DateTimeHelper::uk_to_us_date(Input::get('date')))->whereStatus(Booking::STATUS_BOOKING)->get();
                return Redirect::back()->withInput(Input::all())->withErrors($booker->messages)->withEvents($events);
            } else {
                return Redirect::back()->withInput(Input::all())->withErrors($booker->messages);
            }
        }

        Session::put('booking', $booker->get_booking()->id);

        return Redirect::to('admin/quote/step2');
    }

    public function getStep2()
    {
        $booking = Booking::find(Session::get('booking'));

        $events = Booking::whereDeleted(0)->where('date', DateTimeHelper::uk_to_us_date($booking->date))->whereStatus(Booking::STATUS_BOOKING)->get();

        $this->data['booking'] = $booking;
        $this->data['events'] = $events;

        return View::make('admin.quote.step2', $this->data);
    }

    public function postStep2()
    {
        $booking = Booking::find(Session::get('booking'));

        $client = Client::firstOrCreate(Input::except('_token', 'email_quote'));

        if (Input::has('email_quote')) {
            $email = $booking->package->emailtemplate;

            $email_template = EmailHelper::get_email_viewname($email);

            $date = new DateTime(($booking->date));

            $emailBooking = $booking->replicate();

            $emailBooking->timestamp = $date->getTimestamp();
            // $emailBooking->date = DateTimeHelper::uk_to_us_date($booking->date);

            $this->email = array('link' => Config::get('app.url') . 'booking/' . $emailBooking->email_token);

            // below fix needed because substr won't save on live!
            $startpahar = (substr($emailBooking->start_time, 0, -6)<13)?'AM':'PM';
            $finishpahar = (substr($emailBooking->finish_time, 0, -6)<13)?'AM':'PM';
            $emailBooking->start_time_formatted = (substr($emailBooking->start_time, 0, -6)%12).":".substr($emailBooking->start_time, 3, -3)." ".$startpahar;

            $emailBooking->finish_time_formatted = (substr($emailBooking->finish_time, 0, -6)%12).":".substr($emailBooking->finish_time, 3, -3)." ".$finishpahar;
/*
            $emailBooking->start_time_formatted = substr($emailBooking->start_time, 0, -3);

            $emailBooking->finish_time_formatted = substr($emailBooking->finish_time, 0, -3);*/

            # send email to client if this is a quotation

            EmailHelper::sendQuotation($client->email, $email_template, ['client' => $client, 'booking' => $emailBooking, 'email' => $email], $client, $emailBooking, $email);
        }

        $booking->client()->associate($client);
        $booking->save();

        return Redirect::to('admin/quote/step3');
    }

    public function getStep3()
    {

        $booking = Booking::find(Session::get('booking'));

        $this->data['booking'] = $booking;
        $this->data['occasions'] = Booking::occasions();

        return View::make('admin.quote.step3', $this->data);
    }

    public function postStep3()
    {
        $booking = Booking::find(Session::get('booking'));

        $booking->client()->update(Input::only('address1', 'address2', 'address3', 'address4', 'postcode'));

        $booking->fill(Input::except('address1', 'address2', 'address3', 'address4', 'postcode', 'email_booking', '_token'));

        $booking->date_booked = date('Y-m-d');
        $booking->save();


        return Redirect::to('admin/quote/step4');
    }

    public function getStep4()
    {
        return View::make('admin.quote.step4', $this->data);
    }

    public function postStep4()
    {
        /* $s = Session::all();
        echo '<pre>';
        print_r($s); die;
        // var_dump($booking);
        // echo '<pre>';
        // print_r($booking);
        // exit;
        $booking = Booking::find('2184');*/
        $booking = Booking::with('client')->find(Session::get('booking'));
        $booking->deposit_requested = Input::get('deposit_requested');
        //print_r($booking); die;
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        //Stripe::setApiKey('sk_test_6SbdenVNXQnVpQjeDOf9CnFb');

        try {
            
            $customer = Customer::create([
                'email'  => $booking->client->email,
                'source' => Input::get('stripeToken'),
            ]);

        

            $charge = Charge::create([
                'amount' => $booking->deposit_requested * 100,
                'currency' => 'gbp',
                'customer' => $customer->id,
            ]);

            if ($charge->paid) {
                $booking->deposit_paid = date('Y-m-d');
                $booking->deposit_amount = $charge->amount / 100;
                $booking->deposit_payment_method = 'stripe';
                $booking->balance_requested = $booking->total_cost - $booking->deposit_amount;
                $booking->status = Input::has('email_booking') ? Booking::STATUS_BOOKING : 'booking-no-email';
                $booking->save();
            } else {
                return Redirect::back()->withError($charge->outcome->seller_message);
            }
        } catch (Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];

            $error = 'Error:<br>';
            $error .= 'Status:' . $e->getHttpStatus() . "<br>";
            $error .= 'Type:' . $err['type'] . "<br>";
            $error .= 'Code:' . $err['code'] . "<br>";
            if(isset($err['decline_code']) && !empty($err['decline_code'])):
            $error .= 'Decline Code:' . $err['decline_code'] . "<br>";
                if($err['decline_code'] == 'fraudulent'):
                    $error .= 'Reason: The payment has been declined as the issuer suspects it is fraudulent.<br>';
                endif;
            endif;
            if(isset($err['param']) && !empty($err['param'])):
            $error .= 'Param:' . $err['param'] . "<br>";
            endif;
            //$error .= 'Param:' . $err['param'] . "<br>";
            $error .= 'Message:' . $err['message'] . "<br>";
            $error .= 'Contact your administrator for details';

            return Redirect::back()->with('errors',$error);
        }
        catch (Error\RateLimit $e) {
          // Too many requests made to the API too quickly
            $error = 'Too many requests made to the API too quickly';

            return Redirect::back()->with('errors',$error);
        } catch (Error\InvalidRequest $e) {
            //echo "<pre/>"; print_r($e); die;
          // Invalid parameters were supplied to Stripe's API
            $error = 'Invalid parameters were supplied to Stripe';
            //if($e->message)
            //$error = $e->message;

            return Redirect::back()->with('errors',$error);

        } catch (Error\Authentication $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
            $error = 'Authentication with Stripe failed';

            return Redirect::back()->with('errors',$error);

        } catch (Error\ApiConnection $e) {
          // Network communication with Stripe failed
            $error = 'Network communication with Stripe failed';

            return Redirect::back()->with('errors',$error);

        } catch (Error\Base $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
            $error = 'Unknown Error ';

            return Redirect::back()->with('errors',$error);

        } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
            $error = 'Server error';

            return Redirect::back()->with('errors',$error);
        }

        $booking->client()->update(['stripe_id' => $customer->id]);

        Session::forget('booking');
        Session::forget('email_booking');

        return Redirect::to('admin/bookings/search')->with('message', 'Booking created successfuly');
    }

    public function getClients()
    {
        $clients = Client::whereDeleted(0)->where('name', 'like', Input::get('term') . '%')->get();

        $clients->map(function ($client) {
            $client->value = $client->name;
        });

        return $clients;
    }
   
    public function stripepayment()
        {
            return View::make('admin.payment', $this->data);
        }

    public function update_payment()
        {
               
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        //$booking = Booking::find(Session::get('booking'));
        $deposit_requested = Input::get('deposit_requested');
        try {
            $customer = Customer::create([
                'email'  => Input::get('cus_email'),
                'source' => Input::get('stripeToken'),
            ]);
            

            $charge = Charge::create([
                'amount' => $deposit_requested * 100,
                'currency' => 'gbp',
                'customer' => $customer->id,
                'description' => 'Customer booking charges',
                'receipt_email' => Input::get('cus_email')
            ]);

            if (empty($charge->paid)) {
                return Redirect::back()->with('errors',$charge->outcome->seller_message);
            }
        }
         catch (Error\Card $e) {
            $body = $e->getJsonBody();
            $err  = $body['error'];
            $error = 'Error:<br>';
            $error .= 'Status:' . $e->getHttpStatus() . "<br>";
            $error .= 'Type:' . $err['type'] . "<br>";
            $error .= 'Code:' . $err['code'] . "<br>"; 
			if(isset($err['decline_code']) && !empty($err['decline_code'])):
            $error .= 'Decline Code:' . $err['decline_code'] . "<br>";
				if($err['decline_code'] == 'fraudulent'):
					$error .= 'Reason: The payment has been declined as the issuer suspects it is fraudulent.<br>';
				endif;
			endif;
			if(isset($err['param']) && !empty($err['param'])):
            $error .= 'Param:' . $err['param'] . "<br>";
			endif;
            $error .= 'Message:' . $err['message'] . "<br>";
            $error .= 'Contact your administrator for details';

            return Redirect::back()->with('errors',$error);
        }
		catch (Error\RateLimit $e) {
		  // Too many requests made to the API too quickly
		} catch (Error\InvalidRequest $e) {
		  // Invalid parameters were supplied to Stripe's API
		} catch (Error\Authentication $e) {
		  // Authentication with Stripe's API failed
		  // (maybe you changed API keys recently)
		} catch (Error\ApiConnection $e) {
		  // Network communication with Stripe failed
		} catch (Error\Base $e) {
		  // Display a very generic error to the user, and maybe send
		  // yourself an email
		} catch (Exception $e) {
		  // Something else happened, completely unrelated to Stripe
		}

            return Redirect::to('admin/payment')->with('payment_status', 'Success! The payment has been authorised, please update the customer booking.');  
        }
}
