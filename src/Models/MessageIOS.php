<?php namespace Tencent\XGPush\Models;

class MessageIOS
{

    const MAX_LOOP_TASK_DAYS = 15;

    private $expireTime;

    private $sendTime;

    private $acceptTimes;

    private $custom;

    private $raw;

    private $alert;

    private $badge;

    private $sound;

    private $category;

    private $loopInterval;

    private $loopTimes;


    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }


    public function getExpireTime()
    {
        return $this->expireTime;
    }


    public function setSendTime($sendTime)
    {
        $this->sendTime = $sendTime;
    }


    public function getSendTime()
    {
        return $this->sendTime;
    }


    public function addAcceptTime(TimeInterval $acceptTime)
    {
        $this->acceptTimes[] = $acceptTime;
    }


    public function setCustom($custom)
    {
        $this->custom = $custom;
    }


    public function setRaw($raw)
    {
        $this->raw = $raw;
    }


    public function setAlert($alert)
    {
        $this->alert = $alert;
    }


    public function setBadge($badge)
    {
        $this->badge = $badge;
    }


    public function setSound($sound)
    {
        $this->sound = $sound;
    }


    public function getType()
    {
        return 0;
    }


    public function getCategory()
    {
        return $this->category;
    }


    public function setCategory($category)
    {
        $this->category = $category;
    }


    public function getLoopInterval()
    {
        return $this->loopInterval;
    }


    public function setLoopInterval($loopInterval)
    {
        $this->loopInterval = $loopInterval;
    }


    public function getLoopTimes()
    {
        return $this->loopTimes;
    }


    public function setLoopTimes($loopTimes)
    {
        $this->loopTimes = $loopTimes;
    }


    public function toJson()
    {
        if ( ! empty( $this->raw )) {
            return $this->raw;
        }
        $ret                = $this->custom;
        $aps                = array();
        $ret['accept_time'] = $this->acceptTimeToJson();
        $aps['alert']       = $this->alert;
        if (isset( $this->badge )) {
            $aps['badge'] = $this->badge;
        }
        if (isset( $this->sound )) {
            $aps['sound'] = $this->sound;
        }
        if (isset( $this->category )) {
            $aps['category'] = $this->category;
        }
        $ret['aps'] = $aps;

        return json_encode($ret);
    }


    public function acceptTimeToJson()
    {
        $ret = array();
        foreach ($this->acceptTimes as $acceptTime) {
            /**
             * @var TimeInterval $acceptTime
             */
            $ret[] = $acceptTime->toArray();
        }

        return $ret;
    }


    public function isValid()
    {
        if (is_string($this->raw) && ! empty( $this->raw )) {
            return true;
        }
        if (isset( $this->expireTime )) {
            if ( ! is_int($this->expireTime) || $this->expireTime > 3 * 24 * 60 * 60) {
                return false;
            }
        } else {
            $this->expireTime = 0;
        }
        if (isset( $this->sendTime )) {
            if (strtotime($this->sendTime) === false) {
                return false;
            }
        } else {
            $this->sendTime = "2014-03-13 12:00:00";
        }
        foreach ($this->acceptTimes as $value) {
            if ( ! ( $value instanceof TimeInterval ) || ! $value->isValid()) {
                return false;
            }
        }
        if (isset( $this->custom )) {
            if ( ! is_array($this->custom)) {
                return false;
            }
        } else {
            $this->custom = array();
        }
        if ( ! isset( $this->alert )) {
            return false;
        }
        if ( ! is_string($this->alert) && ! is_array($this->alert)) {
            return false;
        }
        if (isset( $this->badge )) {
            if ( ! is_int($this->badge)) {
                return false;
            }
        }
        if (isset( $this->sound )) {
            if ( ! is_string($this->sound)) {
                return false;
            }
        }
        if (isset( $this->loopInterval )) {
            if ( ! ( is_int($this->loopInterval) && $this->loopInterval > 0 )) {
                return false;
            }
        }
        if (isset( $this->loopTimes )) {
            if ( ! ( is_int($this->loopTimes) && $this->loopTimes > 0 )) {
                return false;
            }
        }
        if (isset( $this->loopInterval ) && isset( $this->loopTimes )) {
            if (( $this->loopTimes - 1 ) * $this->loopInterval + 1 > self::MAX_LOOP_TASK_DAYS) {
                return false;
            }
        }

        return true;
    }
}