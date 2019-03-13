<?php

/*
 * 
 */
trait TestClientTrait
{
    public function addClient()
    {        
        $client = new Client;
        $client->name = 'client one';
        $client->email = 'jo@jo.jo';
        $client->telephone = '0123456789';
        $client->mobile = '07787887887';
        $client->address1 = 'Address 1';
        $client->address2 = 'Address 2';
        $client->address3 = 'Address 3';
        $client->address4 = 'Address 4';
        $client->postcode = 'AB1 2CD';
        $client->heard_about = 'Website';
        $client->deleted = 0;
        $client->save();
        
        return $client;
    }
}

?>
