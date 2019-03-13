<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FullCalendarEvent
 *
 * @author Jo
 */
class FullCalendarEvent
{
    public $id;
    public $title = 'Check availability';
    public $allDay = true;
    public $start;
    public $end;
    public $url;
    public $className;
    public $editable;
    public $startEditable;
    public $durationEditable;
    public $source;
    public $color;
    public $backgroundColor;
    public $borderColor;
    public $textColor;
    
    function __construct($date = null)
    {
        if($date) {
            $this->start = $date;
            $this->end = $date;
        }
    }
    
    public function setStatus($status)
    {
        switch($status) {
            case \Booking::STATUS_PENDING:
                $this->backgroundColor = 'red';
                $this->borderColor = 'red';
                break;
            case \Booking::STATUS_BOOKING:
                $this->backgroundColor = 'green';
                $this->borderColor = 'green';
                break;            
            default:
                break;
        }
    }
}

?>
