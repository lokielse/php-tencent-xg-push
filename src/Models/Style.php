<?php namespace Tencent\XGPush\Models;

class Style
{

    private $builderId;

    private $ring;

    private $vibrate;

    private $clearable;

    private $nId;

    private $ringRaw;

    private $lights;

    private $iconType;

    private $iconRes;

    private $styleId;

    private $smallIcon;


    public function __construct($builderId, $ring = 0, $vibrate = 0, $clearable = 1, $nId = 0, $lights = 1, $iconType = 0, $styleId = 1)
    {
        $this->builderId = $builderId;
        $this->ring      = $ring;
        $this->vibrate   = $vibrate;
        $this->clearable = $clearable;
        $this->nId       = $nId;
        $this->lights    = $lights;
        $this->iconType  = $iconType;
        $this->styleId   = $styleId;
    }


    public function __destruct()
    {
    }


    public function getBuilderId()
    {
        return $this->builderId;
    }


    public function getRing()
    {
        return $this->ring;
    }


    public function getVibrate()
    {
        return $this->vibrate;
    }


    public function getClearable()
    {
        return $this->clearable;
    }


    public function getNId()
    {
        return $this->nId;
    }


    public function getLights()
    {
        return $this->lights;
    }


    public function getIconType()
    {
        return $this->iconType;
    }


    public function getStyleId()
    {
        return $this->styleId;
    }


    public function setRingRaw($ringRaw)
    {
        return $this->ringRaw = $ringRaw;
    }


    public function getRingRaw()
    {
        return $this->ringRaw;
    }


    public function setIconRes($iconRes)
    {
        return $this->iconRes = $iconRes;
    }


    public function getIconRes()
    {
        return $this->iconRes;
    }


    public function setSmallIcon($smallIcon)
    {
        return $this->smallIcon = $smallIcon;
    }


    public function getSmallIcon()
    {
        return $this->smallIcon;
    }


    public function isValid()
    {
        if ( ! is_int($this->builderId) || ! is_int($this->ring) || ! is_int($this->vibrate) || ! is_int($this->clearable) || ! is_int($this->lights) || ! is_int($this->iconType) || ! is_int($this->styleId)) {
            return false;
        }
        if ($this->ring < 0 || $this->ring > 1) {
            return false;
        }
        if ($this->vibrate < 0 || $this->vibrate > 1) {
            return false;
        }
        if ($this->clearable < 0 || $this->clearable > 1) {
            return false;
        }
        if ($this->lights < 0 || $this->lights > 1) {
            return false;
        }
        if ($this->iconType < 0 || $this->iconType > 1) {
            return false;
        }
        if ($this->styleId < 0 || $this->styleId > 1) {
            return false;
        }

        return true;
    }

}