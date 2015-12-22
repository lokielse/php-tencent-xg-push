<?php namespace Tencent\XGPush\Models;

class ClickAction
{

    const TYPE_ACTIVITY = 1;

    const TYPE_URL = 2;

    const TYPE_INTENT = 3;

    const TYPE_PACKAGE = 4;

    private $actionType;

    private $url;

    private $confirmOnUrl;

    private $activity;

    private $intent;

    private $atyAttrIntentFlag;

    private $atyAttrPendingIntentFlag;

    private $packageDownloadUrl;

    private $confirmOnPackageDownloadUrl;

    private $packageName;


    /**
     * 动作类型
     *
     * @param int $actionType 1打开activity或app本身，2打开url，3打开Intent, 4通过包名打开应用
     */
    public function __construct()
    {
        $this->atyAttrIntentFlag           = 0;
        $this->atyAttrPendingIntentFlag    = 0;
        $this->confirmOnPackageDownloadUrl = 1;
    }


    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
    }


    public function setUrl($url)
    {
        $this->url = $url;
    }


    public function setComfirmOnUrl($comfirmOnUrl)
    {
        $this->confirmOnUrl = $comfirmOnUrl;
    }


    public function setActivity($activity)
    {
        $this->activity = $activity;
    }


    public function setIntent($intent)
    {
        $this->intent = $intent;
    }


    public function setAtyAttrIntentFlag($atyAttrIntentFlag)
    {
        $this->atyAttrIntentFlag = $atyAttrIntentFlag;
    }


    public function setAtyAttrPendingIntentFlag($atyAttrPendingIntentFlag)
    {
        $this->atyAttrPendingIntentFlag = $atyAttrPendingIntentFlag;
    }


    public function setPackageDownloadUrl($packageDownloadUrl)
    {
        $this->packageDownloadUrl = $packageDownloadUrl;
    }


    public function setConfirmOnPackageDownloadUrl($confirmOnPackageDownloadUrl)
    {
        $this->confirmOnPackageDownloadUrl = $confirmOnPackageDownloadUrl;
    }


    public function setPackageName($packageName)
    {
        $this->packageName = $packageName;
    }


    public function toJson()
    {
        $ret                = array();
        $ret['action_type'] = $this->actionType;
        $ret['browser']     = array( 'url' => $this->url, 'confirm' => $this->confirmOnUrl );
        $ret['activity']    = $this->activity;
        $ret['intent']      = $this->intent;
        $aty_attr           = array();
        if (isset( $this->atyAttrIntentFlag )) {
            $aty_attr['if'] = $this->atyAttrIntentFlag;
        }
        if (isset( $this->atyAttrPendingIntentFlag )) {
            $aty_attr['pf'] = $this->atyAttrPendingIntentFlag;
        }
        $ret['aty_attr']     = $aty_attr;
        $ret['package_name'] = array(
            'packageDownloadUrl' => $this->packageDownloadUrl,
            'confirm'            => $this->confirmOnPackageDownloadUrl,
            'packageName'        => $this->packageName
        );

        return $ret;
    }


    public function isValid()
    {
        if ( ! isset( $this->actionType )) {
            $this->actionType = 1;
        }
        if ( ! is_int($this->actionType)) {
            return false;
        }
        if ($this->actionType < self::TYPE_ACTIVITY || $this->actionType > self::TYPE_PACKAGE) {
            return false;
        }
        if ($this->actionType == self::TYPE_ACTIVITY) {
            if ( ! isset( $this->activity )) {
                $this->activity = "";

                return true;
            }
            if (isset( $this->atyAttrIntentFlag )) {
                if ( ! is_int($this->atyAttrIntentFlag)) {
                    return false;
                }
            }
            if (isset( $this->atyAttrPendingIntentFlag )) {
                if ( ! is_int($this->atyAttrPendingIntentFlag)) {
                    return false;
                }
            }
            if (is_string($this->activity) && ! empty( $this->activity )) {
                return true;
            }

            return false;
        }
        if ($this->actionType == self::TYPE_URL) {
            if (is_string($this->url) && ! empty( $this->url ) && is_int($this->confirmOnUrl) && $this->confirmOnUrl >= 0 && $this->confirmOnUrl <= 1) {
                return true;
            }

            return false;
        }
        if ($this->actionType == self::TYPE_INTENT) {
            if (is_string($this->intent) && ! empty( $this->intent )) {
                return true;
            }

            return false;
        }
        if ($this->actionType == self::TYPE_PACKAGE) {
            if (is_string($this->packageDownloadUrl) && is_string($this->packageName) && is_int($this->confirmOnPackageDownloadUrl) && $this->confirmOnPackageDownloadUrl >= 0 && $this->confirmOnPackageDownloadUrl <= 1) {
                return true;
            }

            return false;
        }

        return true;
    }
}