<?php
$bookingdate_obj = new DateTime($booking->date);
?>

<style>
@page { margin-top: 2.0em; margin-left: 2.0em; margin-right: 2.0em; }
div,p,td { font-family: helvetica; font-size: 10pt; }
.question { margin-bottom: 10px; font-weight: bold; }
.answer { margin-bottom: 10px; }
.title { margin-bottom: 10px; font-size: 16pt; font-weight: bold; text-align: center; }
.terms p { margin-bottom: 3px; font-size: 9pt; }
.page-break { page-break-before: always; }

</style>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
<td align="left" valign="top" width="50%">

	<div class="question">Company Information</div>
	
	<div class="answer">
		<div>DJ Nick Burrett</div>
		<div>2 Elmcroft</div>
		<div>Bridgwater</div>
		<div>Somerset</div>
		<div>TA6 3PJ</div>
	</div>
	
	<div class="answer">
		<div>01823 240300</div>
		<div>hello@discos.co.uk</div>
		<div>www.discos.co.uk</div>
	</div>
	
	<div class="question">Client Information</div>
	
	<div class="answer">
		<div><?php echo $booking->client->name ?></div>
		<div><?php echo $booking->client->address1 ?></div>
		<div><?php echo $booking->client->address2 ?></div>
		<div><?php echo $booking->client->address3 ?></div>
		<div><?php echo $booking->client->address4 ?></div>
		<div><?php echo $booking->client->postcode ?></div>
	</div>
	
	<div class="answer">
		<div><?php echo $booking->client->telephone ?></div>
		<div><?php echo $booking->client->mobile ?></div>
		<div><?php echo $booking->client->email ?></div>
	</div>
	
	<div class="question">Booking Information</div>
	
	<div class="answer">
		<div>Date: <?php echo date('l jS F Y', strtotime($booking->date)) ?></div>
		<div>Times: <?php echo date('g:i A',strtotime($booking->start_time)) ?> until <?php echo date('g:i A',strtotime($booking->finish_time)) ?></div>
		<div>DJ Arrival: <?php echo date('g:i A',strtotime("-$booking->setup_equipment_time mins", strtotime($booking->start_time))) ?></div>
	</div>
	
	<div class="answer">
		<div><?php echo $booking->venue_name ?></div>
		<div><?php echo $booking->venue_address1 ?></div>
		<div><?php echo $booking->venue_address2 ?></div>
		<div><?php echo $booking->venue_address3 ?></div>
		<div><?php echo $booking->venue_postcode ?></div>
	</div>

</td><td align="left" valign="top" width="50%">
	
	<div class="question">Additional Information</div>
	
	<div class="answer">
		<?php if(!empty($booking->notes)) { echo "<div>$booking->notes</div>"; } ?>
	</div>
	
	<div class="answer">
		<?php if(!empty($booking->bride_firstname)) { echo "<div>Bride's First Name: $booking->bride_firstname</div>"; } ?>
		<?php if(!empty($booking->groom_firstname)) { echo "<div>Groom's First Name: $booking->groom_firstname</div>"; } ?>
		<?php if(!empty($booking->groom_surname)) { echo "<div>Bride & Groom Married Surname: $booking->groom_surname</div>"; } ?>
		<?php if(!empty($booking->birthday_name)) { echo "<div>Birthday Person's Name: $booking->birthday_name</div>"; } ?>
		<?php if(!empty($booking->birthday_age)) { echo "<div>Birthday Person's Age: $booking->birthday_age</div>"; } ?>
	</div>
	
	<div class="question">Financial Information</div>
	
	<div class="answer">
		<div>Total cost: &pound;<?php echo $booking->total_cost ?></div>
		<div>Deposit required: &pound;<?php echo $booking->deposit_requested ?></div>
		<div>Balance to pay: &pound;<?php echo $booking->total_cost - $booking->deposit_requested ?></div>
	
		<?php if($booking->package->name == 'Kids Party DJ' || $booking->package->name == 'Kids Party DJ') : ?>
			<div>Balance due date: <?php echo date('l jS F Y', strtotime("-0 day", strtotime($booking->date))) ?></div>
		<?php else: ?>
			<div>Balance due date: <?php echo date('l jS F Y', strtotime("-21 day", strtotime($booking->date))) ?></div>
		<?php endif; ?>
	
	</div>
	
</td></tr></table>

<hr>

<div class="question">{check:signer1:Please+Tick} - I confirm the disco will be held on the ground floor or can be accessed by the use of ramps or an elevator.</div>

<div class="question">{check:signer1:Please+Tick} - I acknowledge that the DJ will require a guest meal for events lasting longer than 5 hours.  If no meal is provided the DJ reserves the right to go off-site for a meal for 45 minutes.</div>

<div class="question">The CLIENT engaging the DISCO and the DISCO accepting the engagement confirms acceptance of all these terms and conditions by signing the agreement below.</div>

<table width="100%" border="0" style="margin-top: 10mm">
	<tr>
		<td>{signature:signer1:Please+Sign+Here}</td>
		<td>{signature:signer2:Please+Sign+Here}</td>
	</tr>
</table>

<?php if($bookingdate_obj->sub(new DateInterval('P14D')) <= new DateTime()): ?>
	<div class="question">Consent To Start Service Within Cancellation Period</div>
	<div class="answer">If you require us to start work within the 14 day cancellation period, please sign below.  I / we make an express request that the service be started within the cancellation period and are happy to consent to this.</div>

	<table width="100%" border="0" style="margin-top: 10mm">
		<tr>
			<td>{signature:signer1:Please+Sign+Here}</td>
			<td>&nbsp;</td>
		</tr>
	</table>

<?php endif; ?>

<div class="page-break title">Terms &amp; Conditions</div>

<?php if($booking->package->name == 'Kids Party DJ' || $booking->package->name == 'Kids Party DJ') : ?>

	<div class="terms">

	<p>1. The DISCO will require the balance of any engagement fee payable to be made on the day of the party in cash (cheques are not accepted).</p>
	
	<p>2. If the CLIENT cancels the booking or the event does not take place for any reason then full payment will be invoiced to the CLIENT if the DISCO is unable to secure another booking for the same date. Cancellation notification must be in writing and receipt of such notification will be confirmed in writing.</p>
	
	<p>3. The DISCO will conduct themselves in a manner befitting the engagement and will respond to the CLIENTS requests relating to dress code, volume levels, music played, equipment location or any other reasonable request.</p>
	
	<p>4. The DISCO will require access to a properly earthed mains electricity supply, sufficient to allow safe usage of the required equipment for the performance. If the supply is inadequate then the amount of equipment may be reduced. If the DISCO considers that the electricity supply or any other aspect of the event is unsafe then they reserve the right to refuse to start or continue the performance after consultation with the CLIENT. The provisions of clause 2 may also apply.</p>
	
	<p>5. The DISCO will use their best endeavours to attend the function. Should they be prevented from attending for any reason, including accident or sudden illness, then the CLIENT will receive a full refund of all monies paid to the DISCO for that function.</p>
	
	<p>6. Licences for the performance of recorded music are only required at public events. In most cases private parties, such as wedding receptions, birthdays etc which are invitation only and attract no entrance fee do not require a licence. It is the CLIENTS responsibility to obtain such licences if required. Should the DISCO be prevented from performing due to the absence of any appropriate licence or similar permission or should the performance be cancelled for any other reason then the provisions of clause 2 will apply.</p>
	
	<p>7. The DISCO will require adequate setting up time prior to the performance and a sufficient period afterwards to dismantle and remove their equipment from the venue. The amount of time required is dependent on the package selected but 30-45 minutes is usually sufficient.</p>
	
	<p>8. The CLIENT is responsible for providing adequate supervision of all guests, staff and customers at the venue and will be liable for any loss or damage to equipment caused by guests, staff or customers.</p>
	
	<p>9. The CLIENT warrants that they are entitled to use the venue for the purposes of the event and performance and that the event does not breach any law, bye-law or conditions imposed on the property.</p>
	
	<p>10. Any extension of playing time is purely at the discretion of the DISCO and may be subject to other constraints, however they will do their best to accommodate any such request. Fees for extended times are £50 (fifty pounds) per hour or part thereof.</p>
	
	<div class="page-break title">Right To Cancel</div>
	
	<p>You have the right to cancel this contract within 14 days without giving any reason.  The cancellation period will expire after 14 days from the day of the conclusion of the contract.</p>
	
	<p>To exercise the right to cancel, you must inform us of your decision to cancel this contract by a clear statement (e.g. a letter sent by post or e-mail).  Our contact details are as follows:<br>Cool Kids Party, 2 Elmcroft, Bridgwater, Somerset, TA6 3PJ.<br>Telephone: 01278 393100.<br>Email: hello@coolkidsparty.com.</p>
	
	<p>You may use the attached model cancellation form, but it is not obligatory.</p>
	
	<p>To meet the cancellation deadline, it is sufficient for you to send your communication concerning your exercise of the right to cancel before the cancellation period has expired.</p>
	
	<p><strong>Effects Of Cancellation</strong></p>
	
	<p>If you cancel this contract, we will reimburse to you all payments received from you.  We will make the reimbursement without undue delay, and not later than 14 days after the day on which we are informed about your decision to cancel this contract.  We will make the reimbursement using the same means of payment as you used for the initial transaction, unless you have expressly agreed otherwise; in any event, you will not incur any fees as a result of the reimbursement.</p>
	
	<p>If you requested to begin the performance of services during the cancellation period, you shall pay us an amount which is in proportion to what has been performed until you have communicated us your cancellation from this contract, in comparison with the full coverage of the contract. You will lose your right to cancel once the service has been performed.</p>
	
	<table border="1" cellspacing="0" cellpadding="5" width="100%"><tr><td valign="top">
	
	<p><strong>Cancellation Form</strong></p>
	
	<p>To: Cool Kids Party, 2 Elmcroft, Bridgwater, Somerset, TA6 3PJ.<br>Telephone: 01278 393100<br>Email: hello@coolkidsparty.com</p>
	
	<p>I / We [*] hereby give notice that I / We [*] cancel my / our contract for the supply of the following service: Children&rsquo;s Disco / Wedding Disco / Disco &amp; DJ Hire [*]</p>
	<p>Ordered on:</p>
	<p>Name of consumer(s):</p>
	<p>Address of consumer(s):</p>
	<p>Signature of consumer(s) (only if this form is notified on paper):</p>
	<p>Date:</p>
	
	<p>[*] Delete as appropriate</p>
	
	</td></tr></table>
	
	</div>

<?php else: ?>

	<div class="terms">
	
	<p>1. The DISCO will require the balance of any engagement fee payable to be made at least 21 days before the function date.</p>
	
	<p>2. If the CLIENT cancels the booking or the event does not take place for any reason then full payment will be invoiced to the CLIENT if the DISCO is unable to secure another booking for the same date. Cancellation notification must be in writing and receipt of such notification will be confirmed in writing.</p>
	
	<p>3. The DISCO will conduct themselves in a manner befitting the engagement and will respond to the CLIENTS requests relating to dress code, volume levels, music played, equipment location or any other reasonable request.</p>
	
	<p>4. The DISCO will require access to a properly earthed mains electricity supply, sufficient to allow safe usage of the required equipment for the performance. If the supply is inadequate then the amount of equipment may be reduced. If the DISCO considers that the electricity supply or any other aspect of the event is unsafe then they reserve the right to refuse to start or continue the performance after consultation with the CLIENT. The provisions of clause 2 may also apply.</p>
	
	<p>5. The DISCO will use their best endeavours to attend the function. Should they be prevented from attending for any reason, including accident or sudden illness, then the CLIENT will receive a full refund of all monies paid to the DISCO for that function.</p>
	
	<p>6. Licences for the performance of recorded music are only required at public events. In most cases private parties, such as wedding receptions, birthdays etc which are invitation only and attract no entrance fee do not require a licence. It is the CLIENTS responsibility to obtain such licences if required. Should the DISCO be prevented from performing due to the absence of any appropriate licence or similar permission or should the performance be cancelled for any other reason then the provisions of clause 2 will apply.</p>
	
	<p>7. The DISCO will require adequate setting up time prior to the performance and a sufficient period afterwards to dismantle and remove their equipment from the venue. The amount of time required is dependent on the package selected but one hour is usually sufficient.</p>
	
	<p>8. The CLIENT is responsible for providing adequate supervision of all guests, staff and customers at the venue and will be liable for any loss or damage to equipment caused by guests, staff or customers.</p>
	
	<p>9. The CLIENT warrants that they are entitled to use the venue for the purposes of the event and performance and that the event does not breach any law, bye-law or conditions imposed on the property.</p>
	
	<p>10. Any extension of playing time is purely at the discretion of the DISCO and may be subject to other constraints, however they will do their best to accommodate any such request. Fees for extended times are £50 (fifty pounds) per hour or part thereof.</p>
	
	<div class="page-break title">Right To Cancel</div>
	
	<p>You have the right to cancel this contract within 14 days without giving any reason.  The cancellation period will expire after 14 days from the day of the conclusion of the contract.</p>
	
	<p>To exercise the right to cancel, you must inform us of your decision to cancel this contract by a clear statement (e.g. a letter sent by post or e-mail).  Our contact details are as follows:<br>DJ Nick Burrett, 2 Elmcroft, Bridgwater, Somerset, TA6 3PJ.<br>Telephone: 01823 240300.<br>Email: hello@discos.co.uk.</p>
	
	<p>You may use the attached model cancellation form, but it is not obligatory.</p>
	
	<p>To meet the cancellation deadline, it is sufficient for you to send your communication concerning your exercise of the right to cancel before the cancellation period has expired.</p>
	
	<p><strong>Effects Of Cancellation</strong></p>
	
	<p>If you cancel this contract, we will reimburse to you all payments received from you.  We will make the reimbursement without undue delay, and not later than 14 days after the day on which we are informed about your decision to cancel this contract.  We will make the reimbursement using the same means of payment as you used for the initial transaction, unless you have expressly agreed otherwise; in any event, you will not incur any fees as a result of the reimbursement.</p>
	
	<p>If you requested to begin the performance of services during the cancellation period, you shall pay us an amount which is in proportion to what has been performed until you have communicated us your cancellation from this contract, in comparison with the full coverage of the contract. You will lose your right to cancel once the service has been performed.</p>
	
	<table border="1" cellspacing="0" cellpadding="5" width="100%"><tr><td valign="top">
	
	<p><strong>Cancellation Form</strong></p>
	
	<p>To: DJ Nick Burrett, 2 Elmcroft, Bridgwater, Somerset, TA6 3PJ.<br>Telephone: 01823 240300<br>Email: hello@discos.co.uk</p>
	
	<p>I / We [*] hereby give notice that I / We [*] cancel my / our contract for the supply of the following service: Corporate Function / Wedding Disco / Disco &amp; DJ Hire [*]</p>
	<p>Ordered on:</p>
	<p>Name of consumer(s):</p>
	<p>Address of consumer(s):</p>
	<p>Signature of consumer(s) (only if this form is notified on paper):</p>
	<p>Date:</p>
	
	<p>[*] Delete as appropriate</p>
	
	</td></tr></table>
	
	</div>

<?php endif; ?>

<?php if(empty($booking->deposit_payment_method)) :?>
	<!-- IF THERE IS NO DEPOSIT PAID -->
<?php endif; ?>