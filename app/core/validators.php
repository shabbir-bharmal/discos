<?php

Validator::extend('time', function($attribute, $value, $parameters)
{
    $parts = explode(':', $value);
    return (count($parts) == 2) && (is_numeric($parts[0]) && is_numeric($parts[1])) && (0 <= $parts[0] && $parts[0] < 24) && (0 <= $parts[1] && $parts[1] < 60);
});

Validator::extend('name', function($attribute, $value, $parameters)
{
    return count(explode(" ", $value)) > 1;
});

Validator::extend('resolves_to_booking', function($attribute, $value, $parameters)
{
    return count(explode(" ", $value)) > 1;
});

Validator::extend('postcode', function($attribute, $value, $parameters)
{
    return strlen(str_replace(" ", "", $value)) >= 5;
});

