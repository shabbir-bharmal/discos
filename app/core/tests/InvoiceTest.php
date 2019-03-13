<?php

use Illuminate\Support\Collection;

class InvoiceTest extends TestCase
{
    use TestClientTrait;
    use TestBookingTrait;
    
    /** test */
    public function can_add_client()
    {        
        $this->addClient();
        
        #QB::shouldReceive()->once()->with();
        
        $client = QB::getClient(Client::find(1));
    }
    
    /** test */
    public function can_send()
    {        
        $client = $this->addClient();
        #$client->qb_customer_ref = 60; // already exists on the sandbox
        $client->save();
        
        $booking = $this->addBooking($client, Package::find(1));
        $booking->date = '2014-12-12'; // set in past so it will be valid
        $booking->balance_amount = 100; // needed for the invoice
        $booking->save();
        
        $result = QB::send(new Collection([$booking]));
       
        
        $this->assertTrue($result);
    }
    
    /** test causes error re OAUTH_SIG_METHOD_HMACSHA1 */
    public function can_find_existing_customer()
    {
        $client = $this->addClient();
        
        $this->assertNull($client->qb_customer_ref);
        
        QB::getClient($client);
        
        $this->assertNotNull($client->qb_customer_ref);
    }
}

?>
