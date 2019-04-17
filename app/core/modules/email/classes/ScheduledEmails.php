<?php


class ScheduledEmails
{
    private $tasker;
    private $mode;

    const LIVE_MODE = 'live';
    const TEST_MODE = 'test';

    const TEST_EMAIL_RECIPIENT = 'info@ikeras.lt';
    const TEST_EMAIL_CC = '';

    public function __construct(ScheduledEmailsInterface $tasksHelper, $mode)
    {
        $this->tasker = $tasksHelper;
        $this->mode = $mode;
    }

    public function run()
    {
        $scheduled_emails = $this->tasker->get_pending_emails();
        Log::info($scheduled_emails);
        foreach ($scheduled_emails as $scheduled_email) :
            $email_data_collection = $this->tasker->get_data_for_emails($scheduled_email);
            Log::info($email_data_collection);
            $this->sendEmails($scheduled_email, $email_data_collection);
        endforeach;
    }

    public static function getPendingEmails(\DateTime $date)
    {
        return EmailTemplate::schedules()->filter(function ($email) use ($date) {

            $ready = $email->scheduled == 1 && $email->last_schedule != $date->format('Y-m-d') && $date->format('H') == $email->execution_hour;

            switch ($email->regularity) {
                case EmailTemplate::REGULARITY_WEEKLY:
                    return $ready && (new DateTime)->format('l') == $email->day_of_week;

                case EmailTemplate::REGULARITY_MONTHLY:
                    return $ready && (new DateTime)->format('j') == $email->day_of_month;

                case EmailTemplate::REGULARITY_DAILY:
                default:
                    return $ready;
            }
        });
    }

	private function sendEmails(EmailTemplate $scheduled_email, $email_data_collection)
	{
		foreach ($email_data_collection as $email_data) :
			$data         = array();
			$data['data'] = $email_data;
			$to           = $this->getRecipient($scheduled_email->recipient, $email_data);
			$cc           = $this->getCc($scheduled_email->cc);
			if(empty($email_data)){
				Log::info('No Follow Up Emails found for '.$scheduled_email->name);
			}else {
				if (isset($email_data_collection[0]) || (isset($email_data->client) && !empty($email_data->client))) {
					\Mail::send(Helpers\EmailHelper::get_email_viewname($scheduled_email), $data, function ($mail) use ($email_data, $scheduled_email, $to, $cc) {
						$mail->to(Str::lower($to));
						if ($scheduled_email->name == 'DISCOSCOUK') {
							$mail->subject($scheduled_email->name);
						} else {
							$mail->subject($this->tasker->get_subject($scheduled_email->subject, is_a($email_data, 'Booking') || is_a($email_data, 'FollowUp') ? $email_data : null));
						}
						if ($scheduled_email->cc != '') {
							$mail->bcc(ScheduledEmails::getCc($scheduled_email->cc));
						}
						if ($scheduled_email->reply_to != '' && $scheduled_email->reply_to != null) {
							$mail->replyTo($scheduled_email->reply_to);
						}
						if ($scheduled_email->email_from != '') {
							$mail->from($scheduled_email->email_from, $scheduled_email->name_from);
						}
					});
					Log::info('Scheduled email sent to ' . $to . ' ' .(isset($email_data_collection[0]))? '':$email_data->client_id . ' using email template ' . $scheduled_email->name);
					$this->logForTest($scheduled_email->recipient, $email_data);
				} else {
					Log::info('No Client data found for Booking'.$email_data->booking_id.', client '.$email_data->client_id .' for '. $scheduled_email->name . ' template.');
				}
			}
		endforeach;
		$scheduled_email->last_schedule = (new DateTime)->format('Y-m-d');
		$scheduled_email->save();
	}

    public function getRecipient($email_recipient, $email_data)
    {
        return ($this->mode == self::LIVE_MODE) ? $this->getLiveRecipient($email_recipient, $email_data) : self::TEST_EMAIL_RECIPIENT;
    }

    private function getLiveRecipient($email_recipient, $email_data)
    {
        return ($email_recipient == EmailTemplate::RECIPIENT_CLIENT) ? $email_data->client->email : $email_recipient;
    }

    public function getCc($email_cc)
    {
        if ($this->mode == self::LIVE_MODE) {
            return $email_cc ?: '';
        }

        return self::TEST_EMAIL_CC;
    }

    private function logForTest($email_recipient, $email_data)
    {
        if ($this->mode == self::LIVE_MODE) {
            return;
        }

        Log::info('This is test mode. Email will have been sent to ' . $this->getLiveRecipient($email_recipient, $email_data));
    }
}
