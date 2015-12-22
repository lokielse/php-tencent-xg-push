<?php namespace Tencent\XGPush\Models;

class MessageAndroid
{

    const TYPE_NOTIFICATION = 1;
    const TYPE_MESSAGE = 2;

    const MAX_LOOP_TASK_DAYS = 15;

    private $title;

    private $content;

    private $expireTime;

    private $sendTime;

    private $acceptTimes;

    private $type;

    private $multiPkg;

    /**
     * @var Style
     */
    private $style;

    /**
     * @var ClickAction
     */
    private $action;

    private $custom;

    private $raw;

    private $loopInterval;

    private $loopTimes;


    public function __construct()
    {
        $this->acceptTimes = array();
        $this->multiPkg    = 0;
        $this->raw         = "";
    }


    public function setTitle($title)
    {
        $this->title = $title;
    }


    public function setContent($content)
    {
        $this->content = $content;
    }


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


    /**
     * 消息类型
     *
     * @param int $type 1：通知 2：透传消息
     */
    public function setType($type)
    {
        $this->type = $type;
    }


    public function getType()
    {
        return $this->type;
    }


    public function setMultiPkg($multiPkg)
    {
        $this->multiPkg = $multiPkg;
    }


    public function getMultiPkg()
    {
        return $this->multiPkg;
    }


    public function setStyle($style)
    {
        $this->style = $style;
    }


    public function setAction($action)
    {
        $this->action = $action;
    }


    public function setCustom($custom)
    {
        $this->custom = $custom;
    }


    public function setRaw($raw)
    {
        $this->raw = $raw;
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
        $ret = array();
        if ($this->type == self::TYPE_NOTIFICATION) {
            $ret['title']       = $this->title;
            $ret['content']     = $this->content;
            $ret['accept_time'] = $this->acceptTimeToJson();
            $ret['builder_id']  = $this->style->getBuilderId();
            $ret['ring']        = $this->style->getRing();
            $ret['vibrate']     = $this->style->getVibrate();
            $ret['clearable']   = $this->style->getClearable();
            $ret['n_id']        = $this->style->getNId();
            if ( ! is_null($this->style->getRingRaw())) {
                $ret['ring_raw'] = $this->style->getRingRaw();
            }
            $ret['lights']    = $this->style->getLights();
            $ret['icon_type'] = $this->style->getIconType();
            if ( ! is_null($this->style->getIconRes())) {
                $ret['icon_res'] = $this->style->getIconRes();
            }
            $ret['style_id'] = $this->style->getStyleId();
            if ( ! is_null($this->style->getSmallIcon())) {
                $ret['small_icon'] = $this->style->getSmallIcon();
            }
            $ret['action'] = $this->action->toJson();
        } else {
            if ($this->type == self::TYPE_MESSAGE) {
                $ret['title']       = $this->title;
                $ret['content']     = $this->content;
                $ret['accept_time'] = $this->acceptTimeToJson();
            }
        }
        $ret['custom_content'] = $this->custom;

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
        if ( ! isset( $this->title )) {
            $this->title = "";
        } else {
            if ( ! is_string($this->title) || empty( $this->title )) {
                return false;
            }
        }
        if ( ! isset( $this->content )) {
            $this->content = "";
        } else {
            if ( ! is_string($this->content) || empty( $this->content )) {
                return false;
            }
        }
        if ( ! is_int($this->type) || $this->type < self::TYPE_NOTIFICATION || $this->type > self::TYPE_MESSAGE) {
            return false;
        }
        if ( ! is_int($this->multiPkg) || $this->multiPkg < 0 || $this->multiPkg > 1) {
            return false;
        }
        if ($this->type == self::TYPE_NOTIFICATION) {
            if ( ! ( $this->style instanceof Style ) || ! ( $this->action instanceof ClickAction )) {
                return false;
            }
            if ( ! $this->style->isValid() || ! $this->action->isValid()) {
                return false;
            }
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
            $this->sendTime = "2013-12-19 17:49:00";
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