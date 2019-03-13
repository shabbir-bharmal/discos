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

    'mandrill' => array(
        'secret' => 'Img98t0rNNWrQnzNwfnvaw',
    ),


    'mailgun' => array(
        'domain' => 'mg.djnickburrett.com',
        'secret' => 'key-946e9bc5ed24f7496e21e48c5503cc4e',
    ),

    'stripe' => array(
        'model'  => 'User',
        'secret' => $_ENV['STRIPE_KEY'],
    ),

);
