<?php

interface BookingActionInterface {

    public function process($input = []);

    public function get_rules();

    public function get_unavailable_text();

    public function get_site();

}