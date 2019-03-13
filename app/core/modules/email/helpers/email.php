<?php

if (!function_exists('get_email_viewname')) {
    function get_email_viewname(\EmailTemplate $email)
    {
        return "templates.emails.$email->view";
    }
}

?>
