<?php

class BlockQuotes extends CoolKidsParty implements BookingActionInterface {

    public static $rules = [
        // add availability rules
    ];

    public $package;
    public $start;
    public $end;
    public $slots = false;

    public $messages;
    private $debug;

    function __construct($debug = false)
    {
        $this->messages = new \Illuminate\Support\MessageBag();
        $this->debug = $debug;
    }

    public function process($input = [])
    {
        // not used
    }

    public function get_rules()
    {
        return self::$rules;
    }

    public function get_unavailable_text()
    {
        return 'Unfortunately this time is not available.';
    }

    public static function getPackageName()
    {
        return "Kid%";
    }
}