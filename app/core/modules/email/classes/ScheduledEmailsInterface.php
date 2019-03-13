<?php


interface ScheduledEmailsInterface
{
    #public function process_tasks();
    public function get_pending_emails();
    public function get_data_for_emails(EmailTemplate $scheduled_email);
    public function get_subject($template_subject, $booking = null);
}
