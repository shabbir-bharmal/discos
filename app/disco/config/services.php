<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => array(
		'domain' => 'mg.djnickburrett.com',
		'secret' => 'key-8c8169af9ca7ad688499639e0d1e8bb3',
	),

	'mandrill' => array(
		'secret' => 'tX8NvzdftMcpAQnAAWb4UQ',
	),

	'stripe' => array(
		'model'  => 'User',
		'secret' => '',
	),

);
