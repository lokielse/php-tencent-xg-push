<?php namespace Tencent\XGPush\Models;

class TimeInterval
{

    private $startHour;

    private $startMin;

    private $endHour;

    private $endMin;


    public function __construct($startHour, $startMin, $endHour, $endMin)
    {
        $this->startHour = $startHour;
        $this->startMin  = $startMin;
        $this->endHour   = $endHour;
        $this->endMin    = $endMin;
    }


    public function toArray()
    {
        return array(
            'start' => array( 'hour' => strval($this->startHour), 'min' => strval($this->startMin) ),
            'end'   => array( 'hour' => strval($this->endHour), 'min' => strval($this->endMin) )
        );
    }


    public function isValid()
    {
        if ( ! is_int($this->startHour) || ! is_int($this->startMin) || ! is_int($this->endHour) || ! is_int($this->endMin)) {
            return false;
        }
        if ($this->startHour >= 0 && $this->startHour <= 23 && $this->startMin >= 0 && $this->startMin <= 59 && $this->endHour >= 0 && $this->endHour <= 23 && $this->endMin >= 0 && $this->endMin <= 59) {
            return true;
        } else {
            return false;
        }
    }

}