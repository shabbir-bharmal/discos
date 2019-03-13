<?php

trait TestEmailTrait
{
    public function addEmails()
    {        
        $quotation = new EmailTemplate;
        $quotation->type = EmailTemplate::TYPE_QUOTATION;
        $quotation->name = 'Children\'s Party Disco';
        $quotation->view = 'email_1';
        $quotation->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $quotation->subject = 'Cool Kids Party Quotation';
        $quotation->email_from = 'CoolKidsParty@coolkidsparty.com';
        $quotation->name_from = 'Nick DJ';
        $quotation->filter = '';
        $quotation->cc = 'cc@email.com';
        $quotation->data = '';
        $quotation->save();
        
        $quotation = new EmailTemplate;
        $quotation->type = EmailTemplate::TYPE_QUOTATION;
        $quotation->name = 'Primary School Disco';
        $quotation->view = 'email_2';
        $quotation->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $quotation->subject = 'Cool Kids Party Quotation';
        $quotation->email_from = 'CoolKidsParty@coolkidsparty.com';
        $quotation->name_from = 'Nick DJ';
        $quotation->filter = '';
        $quotation->data = '';
        $quotation->save();
        
        $quotation = new EmailTemplate;
        $quotation->type = EmailTemplate::TYPE_QUOTATION;
        $quotation->name = 'Wedding Reception';
        $quotation->view = 'email_3';
        $quotation->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $quotation->subject = 'Wedding DJ Quotation';
        $quotation->email_from = 'TheWeddingDJ@theweddingdj.co.uk';
        $quotation->name_from = 'Nick DJ';
        $quotation->filter = '';
        $quotation->data = '';
        $quotation->save();
        
        $quotation = new EmailTemplate;
        $quotation->type = EmailTemplate::TYPE_QUOTATION;
        $quotation->name = 'Others';
        $quotation->view = 'email_4';
        $quotation->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $quotation->subject = 'Disco Quotation';
        $quotation->email_from = 'DJNickBurrett@discos.co.uk';
        $quotation->name_from = 'Nick DJ';
        $quotation->filter = '';
        $quotation->data = '';
        $quotation->save();
        
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_REGULAR;
        $regular->name = 'Unpaid deposits and balances';
        $regular->recipient = 'jo@jobult.co.uk';
        $regular->subject = 'Deposits & Balances Report (from scheduler)';
        $regular->view = 'email_6';
        $regular->email_from = 'CoolKidsParty@coolkidsparty.com';
        $regular->name_from = 'Nick DJ';
        $regular->filter = 'always';
        $regular->data = 'unpaid';  
        $regular->execution_hour = 10;
        $regular->save();
        
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_REGULAR;
        $regular->name = 'Future Bookings Report';
        $regular->recipient = 'jo@jobult.co.uk';
        $regular->subject = 'Future Bookings Report (from scheduler)';
        $regular->view = 'email_7';
        $regular->filter = 'always';
        $regular->email_from = 'CoolKidsParty@coolkidsparty.com';
        $regular->name_from = 'Nick DJ';
        $regular->data = 'future_bookings';
        $regular->execution_hour = 10;      
        $regular->save();
        
        $event = new EmailTemplate;
        $event->type = EmailTemplate::TYPE_EVENT_PARTY;
        $event->name = 'COOL KIDS PARTY FACEBOOK UPDATE';
        $event->recipient = 'ghats967libya@m.facebook.com';
        $event->subject = 'Facebook post (from scheduler)';
        $event->view = 'email_8';
        $event->data = 'current';
        $event->filter = 'event';
        $event->email_from = 'ask@coolkidsparty.com';
        $event->name_from = 'Cool Kids Party';
        $event->cc = 'ask@coolkidsparty.com';
        $event->packages = array(1,2,3,4,5,6);
        $event->save();

        $event = new EmailTemplate;
        $event->type = EmailTemplate::TYPE_EVENT_PARTY_FINISHED;
        $event->name = 'Party just finished';
        $event->recipient = 'jo@finished.com';
        $event->subject = 'Party just finished (from scheduler)';
        $event->view = 'party-finished';
        $event->data = 'current';
        $event->filter = 'event';
        $event->email_from = 'ask@coolkidsparty.com';
        $event->name_from = 'Cool Kids Party';
        $event->cc = 'ask@coolkidsparty.com';
        $event->packages = array(1,2,3,4,5,6);
        $event->save();
        
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_REGULAR;
        $regular->name = 'BOOKING REMINDER';
        $regular->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $regular->subject = 'We\'re only days away from your wedding!';
        $regular->view = 'email_9';
        $regular->filter = 'week_before';
        $regular->email_from = 'ask@theweddingdj.co.uk';
        $regular->name_from = 'The Wedding DJ';
        $regular->data = 'current';
        $regular->execution_hour = 10;
        $regular->cc = 'ask@theweddingdj.co.uk';
        $regular->packages = array(13,14,15,16);
        $regular->save();
        
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_REGULAR;
        $regular->name = 'GET TESTIMONIAL';
        $regular->recipient = EmailTemplate::RECIPIENT_CLIENT;
        $regular->subject = 'We Want Your Party Feedback!';
        $regular->view = 'email_10';
        $regular->filter = 'week_after';
        $regular->email_from = 'ask@coolkidsparty.com';
        $regular->name_from = 'Cool Kids Party';
        $regular->cc = 'ask@coolkidsparty.com';
        $regular->data = 'current';
        $regular->execution_hour = 10;
        $regular->packages = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $regular->save();
        
        $regular = new EmailTemplate;
        $regular->type = EmailTemplate::TYPE_EVENT_STATUS_CHANGED;
        $regular->name = 'Status changed';
        $regular->recipient = 'test@email.com';
        $regular->subject = 'Status changed!';
        $regular->view = 'email_13';
        $regular->filter = 'event';
        $regular->email_from = 'ask@coolkidsparty.com';
        $regular->name_from = 'Cool Kids Party';
        $regular->cc = 'ask@coolkidsparty.com';
        $regular->data = 'current';
        $regular->execution_hour = 10;
        $regular->packages = array(1,2,3,4,5,6,7,8,9,10,11,12);
        $regular->save();
    }
}

?>
