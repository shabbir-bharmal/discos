<?php

namespace Helpers;

use Client;
use Illuminate\Support\Collection;
use Config;
use Log;

class QuickBookHelper
{
    public static function populateInvoice(Collection $bookings, $customer_ref)
    {        
        $invoice = new \IPPInvoice();
        
        foreach($bookings as $booking) {
            
            $line = new \IPPLine();
            $line->Description = $booking->package->name . " at $booking->venue_name on $booking->date";
            $line->Amount = $booking->invoice_amount;
            $line->DetailType = 'SalesItemLineDetail';
            $line->SalesItemLineDetail = new \IPPSalesItemLineDetail();
            $line->SalesItemLineDetail->UnitPrice = $booking->invoice_amount;
            $line->SalesItemLineDetail->Qty = 1;
            $line->SalesItemLineDetail->ItemRef = new \IPPReferenceType();
            $date = $booking->date;
            $line->SalesItemLineDetail->ServiceDate = DateTimeHelper::uk_to_us_date($date);
            if (strpos($booking->package->name, "Children") !== FALSE) {
                $line->SalesItemLineDetail->ItemRef->name = "Children's Discos";
                $line->SalesItemLineDetail->ItemRef->value = Config::get('invoices.children_service_type_id');
            } else {
                $line->SalesItemLineDetail->ItemRef->name = 'DJ & Disco Hire';
                $line->SalesItemLineDetail->ItemRef->value = Config::get('invoices.other_service_type_id');
            }
            $invoice->Line[] = $line;
        }
        
        $customerRef = new \IPPReferenceType();
        $customerRef->value = $customer_ref;
        $invoice->CustomerRef = $customerRef;
        
        $invoice->BillEmail = new \IPPEmailAddress();
        $invoice->BillEmail->Address = $bookings->first()->client->email;
        $invoice->EmailStatus = 'NeedToSend';
        $due_date = $bookings->first()->date;
        $invoice->DueDate = DateTimeHelper::uk_to_us_date($due_date);
        $invoice->CustomerMemo = Config::get('invoices.invoice_message');
        
        $invoice->SalesTermRef = new \IPPReferenceType();
        $invoice->SalesTermRef->value = Config::get('invoices.terms_due_on_receipt_id');
        
        // $invoice->TotalAmt = $total; - QB will calculate this
        
        Log::info('Sending invoice object: '.print_r($invoice, true));
        
        return $invoice;
    }
    
    public static function populateCustomer(Client $client)
    {        
        $customerObj = new \IPPCustomer();
        if ($client->qb_customer_ref > 0) $customerObj->Id = $client->qb_customer_ref;        
        $customerObj->DisplayName = $client->name;
        $names = explode(" ", $client->name);
        $customerObj->GivenName = array_shift($names);
        $customerObj->FamilyName = implode(" ", $names);
        $customerObj->BillAddr = new \IPPPhysicalAddress();
        $customerObj->BillAddr->Line1 = $client->address1;
        $customerObj->BillAddr->Line2 = $client->address2;
        $customerObj->BillAddr->City = $client->address3;
        $customerObj->BillAddr->CountrySubDivisionCode = $client->address4;
        $customerObj->BillAddr->PostalCode = trim($client->postcode);
        //$customerObj->BillAddr->Country = 'UK'; //Nick doesn't want this coming through
        
        $customerObj->PrimaryEmailAddr = new \IPPEmailAddress();
        $customerObj->PrimaryEmailAddr->Address = $client->email;
        
        $customerObj->PrimaryPhone = new \IPPTelephoneNumber();
        $customerObj->PrimaryPhone->FreeFormNumber = $client->telephone;
        $customerObj->Mobile = new \IPPTelephoneNumber();
        $customerObj->Mobile->FreeFormNumber = $client->mobile;
        
        return $customerObj;
    }
    
    public static function updateCustomerObject(Client $client, $customerObj)
    {           
        $customerObj->DisplayName = $client->name;
        $names = explode(" ", $client->name);
        $customerObj->GivenName = array_shift($names);
        $customerObj->FamilyName = implode(" ", $names);
        
        $customerObj->BillAddr = new \IPPPhysicalAddress();
        $customerObj->BillAddr->Line1 = $client->address1;
        $customerObj->BillAddr->Line2 = $client->address2;
        $customerObj->BillAddr->City = $client->address3;
        $customerObj->BillAddr->CountrySubDivisionCode = $client->address4;
        $customerObj->BillAddr->PostalCode = trim($client->postcode);
        //$customerObj->BillAddr->Country = 'UK'; //Nick doesn't want this coming through
        
        $customerObj->PrimaryEmailAddr = new \IPPEmailAddress();
        $customerObj->PrimaryEmailAddr->Address = $client->email;
        
        $customerObj->PrimaryPhone = new \IPPTelephoneNumber();
        $customerObj->PrimaryPhone->FreeFormNumber = $client->telephone;
        $customerObj->Mobile = new \IPPTelephoneNumber();
        $customerObj->Mobile->FreeFormNumber = $client->mobile;
        
        return $customerObj;
    }
}

?>
