<?php

use Illuminate\Support\Collection;
use PhpImap\Mailbox;

class FollowUp extends Eloquent
{

    public $fillable = [
        'client_id',
        'template',
        'index',
        'booking_id',
    ];

    public static function refresh()
    {
        Log::info('check imap');
        try {
            $mailbox = imap_open('{merlin.xssl.net:993/ssl/novalidate-cert}INBOX', 'followup@discos.uk', 'Quartley246');
        } catch (Exception $e) {
            Log::error(imap_errors());
            
            die();
        }
        
        $msgCount = imap_check($mailbox)->Nmsgs;

        $uids = imap_search($mailbox, 'SINCE "' . date('d M Y', strtotime('-7 days')) . '"');

        Log::info('msgCount: ' . count($uids));
        Log::info('uids: ');
        Log::info($uids);

        if ($msgCount) {
            Log::info('INN');
            if (count($uids) == 1) {
                $mails = new Collection(imap_fetch_overview($mailbox, $uids[0], FT_UID));
            }else{
                $mails = new Collection(imap_fetch_overview($mailbox, implode(',', $uids), FT_UID));
            }
            $followUps = FollowUp::all();

            foreach ($followUps as $followUp) {
                $matchedMails = $mails->filter(function ($mail) use ($followUp) {
                    $address = imap_rfc822_parse_adrlist($mail->from, 'no-host');
                    $from = $address[0]->mailbox . '@' . $address[0]->host;

                    return $mail->date > $followUp->created_at && $from == $followUp->client->email;
                });

                if ($matchedMails->count()) {
                    $followUp->delete();
                }
            }
        }

        imap_close($mailbox);
    }

    public function client()
    {
        return $this->belongsTo('Client');
    }

    public function booking()
    {
        return $this->belongsTo('Booking');
    }
}
