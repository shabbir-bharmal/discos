@if( $data->bride_firstname != '' && $data->groom_firstname != '')
Dear {{ $data->bride_firstname }} and {{ $data->groom_firstname }}
@elseif( Helpers\StringsHelper::title($data->client->name) != '')
Dear {{ $data->client->name }}
@else
Dear {{ Helpers\StringsHelper::firstName($data->client->name) }}
@endif<br><br>

Thank you for choosing me to provide your entertainment on {{date("l jS \of F Y",strtotime($data->date))}}.<br><br>

If for any reason you were not completely satisfied with the service you received from me, please reply to this email with further details as I would hate to have an unhappy customer.<br><br>

Alternatively, I would be incredible grateful if you could leave a great review for me on my Facebook page<br>
Step 1: Go to <a href="https://www.facebook.com/pg/DiscoKaraokeUK/reviews/">https://www.facebook.com/pg/DiscoKaraokeUK/reviews/</a><br>
Step 2: Where it says "Would you recommend DJ Nick Burrett?" - click YES<br>
Step 3: Type your review and press the "POST" button<br><br>

If you are feeling REALLY REALLY generous, please also leave a review on the "Buy With Confidence" website.<br>
Step 1: Go to <a href="https://www.buywithconfidence.gov.uk/rate-a-trader-detail/?feedback_from=search-result&trader_id=13389">https://www.buywithconfidence.gov.uk</a><br>
Step 2: Fill out the form<br>
Step 3: Press "SUBMIT"<br><br>

<strong>If you leave a great review on both websites, to say thanks I'll reward you with a £10 gift voucher to spend online at Amazon.co.uk :-)</strong><br><br>

Kind Regards<br>
Nick Burrett<br>
<a href="https://www.discos.co.uk">www.discos.co.uk</a><br>
01823 240300