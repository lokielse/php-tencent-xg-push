<?php namespace Tencent\XGPush;

use Tencent\XGPush\Exceptions\Exception;
use Tencent\XGPush\Http\RequestBase;
use Tencent\XGPush\Models\ClickAction;
use Tencent\XGPush\Models\MessageAndroid;
use Tencent\XGPush\Models\MessageIOS;
use Tencent\XGPush\Models\ParamsBase;
use Tencent\XGPush\Models\Style;
use Tencent\XGPush\Models\TagTokenPair;

/**
 * Class XingeApp
 * @package Tencent\XGPush
 */
class XGPush
{

    const DEVICE_ALL = 0;
    const DEVICE_BROWSER = 1;
    const DEVICE_PC = 2;
    const DEVICE_ANDROID = 3;
    const DEVICE_IOS = 4;
    const DEVICE_WIN_PHONE = 5;

    const IOS_ENV_PROD = 1;
    const IOS_ENV_DEV = 2;

    const IOS_MIN_ID = 2200000000;

    const ENDPOINT = 'http://openapi.xg.qq.com/v2/';

    protected $accessId;

    protected $secretKey;


    /**
     * @param $accessId
     * @param $secretKey
     */
    public function __construct($accessId, $secretKey)
    {
        $this->accessId  = $accessId;
        $this->secretKey = $secretKey;
    }


    /**
     * 使用默认设置推送消息给单个android设备
     *
     * @param $title
     * @param $content
     * @param $token
     *
     * @return mixed
     * @throws Exception
     */
    public function pushTokenAndroid($title, $content, $token)
    {
        $message = new MessageAndroid();
        $message->setTitle($title);
        $message->setContent($content);
        $message->setType(MessageAndroid::TYPE_NOTIFICATION);
        $message->setStyle(new Style(0, 1, 1, 1, 0));
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_ACTIVITY);
        $message->setAction($action);

        return $this->pushSingleDevice($token, $message);
    }


    /**
     * 推送消息给单个设备
     *
     * @param                           $deviceToken
     * @param MessageAndroid|MessageIOS $message
     * @param int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function pushSingleDevice($deviceToken, $message, $environment = 0)
    {

        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        $params['send_time']   = $message->getSendTime();
        if ($message instanceof MessageAndroid) {

            $params['multi_pkg'] = $message->getMultiPkg();
        }

        $params['device_token'] = $deviceToken;
        $params['message_type'] = $message->getType();
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        return $this->api('push/single_device', $params);
    }


    /**
     * @param $message
     *
     * @return bool
     * @throws Exception
     */
    private function validateMessageType($message)
    {
        if (( $this->accessId ) >= XGPush::IOS_MIN_ID and $message instanceof MessageIOS) {
            //ok
        } elseif (( $this->accessId ) < XGPush::IOS_MIN_ID and $message instanceof MessageAndroid) {
            //ok
        } else {
            throw new Exception('message type not fit accessId', -1);
        }
    }


    /**
     * @param $deviceType
     *
     * @throws Exception
     */
    private function validateDeviceType($deviceType)
    {
        if ( ! is_int($deviceType) || $deviceType < 0 || $deviceType > 5) {
            throw new Exception('deviceType not valid', -1);
        }
    }


    /**
     * @param $message
     *
     * @throws Exception
     */
    private function validateMessage($message)
    {
        if ( ! ( $message instanceof MessageAndroid ) && ! ( $message instanceof MessageIOS )) {
            throw new Exception('message not valid', -1);
        }

        if ( ! $message->isValid()) {
            throw new Exception('message not valid', -1);
        }
    }


    /**
     * @param $tagsOp
     *
     * @throws Exception
     */
    private function validateTagsOp($tagsOp)
    {
        if ( ! is_string($tagsOp) || ( $tagsOp != 'AND' && $tagsOp != 'OR' )) {
            throw new Exception('tagsOp not valid', -1);
        }
    }


    /**
     * @param $message
     * @param $environment
     *
     * @throws Exception
     */
    private function validateEnvironment($message, $environment)
    {
        if ($message instanceof MessageIOS) {
            if ($environment != XGPush::IOS_ENV_DEV && $environment != XGPush::IOS_ENV_PROD) {
                throw new Exception('ios message environment invalid', -1);
            }
        }
    }


    /**
     * @param $uri
     * @param $params
     *
     * @return mixed
     */
    public function api($uri, $params)
    {
        $url            = sprintf('%s/%s', rtrim(self::ENDPOINT, '/'), $uri);
        $paramsBase     = new ParamsBase($params);
        $sign           = $paramsBase->generateSign(RequestBase::METHOD_POST, $url, $this->secretKey);
        $params['sign'] = $sign;

        $requestBase = new RequestBase();

        $result = $requestBase->exec($url, $params, RequestBase::METHOD_POST);

        $data = $this->json2Array($result);

        return $data;
    }


    /**
     * @param $json
     *
     * @return mixed
     */
    protected function json2Array($json)
    {
        return json_decode($json, true);
    }


    /**
     * 使用默认设置推送消息给单个ios设备
     *
     * @param $content
     * @param $token
     * @param $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function pushTokenIOS($content, $token, $environment)
    {
        $message = new MessageIOS();
        $message->setAlert($content);

        return $this->pushSingleDevice($token, $message, $environment);
    }


    /**
     * 使用默认设置推送消息给单个android版账户
     *
     * @param $title
     * @param $content
     * @param $account
     *
     * @return mixed
     * @throws Exception
     */
    public function pushAccountAndroid($title, $content, $account)
    {
        $message = new MessageAndroid();
        $message->setTitle($title);
        $message->setContent($content);
        $message->setType(MessageAndroid::TYPE_NOTIFICATION);
        $message->setStyle(new Style(0, 1, 1, 1, 0));
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_ACTIVITY);
        $message->setAction($action);

        return $this->pushSingleAccount(0, $account, $message);
    }


    /**
     * 推送消息给单个账户
     *
     * @param  int                       $deviceType
     * @param  string                    $account
     * @param  MessageAndroid|MessageIOS $message
     * @param  int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushSingleAccount($deviceType, $account, $message, $environment = 0)
    {
        $this->validateDeviceType($deviceType);
        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        $params['send_time']   = $message->getSendTime();
        if ($message instanceof MessageAndroid) {
            $params['multi_pkg'] = $message->getMultiPkg();
        }
        $params['device_type']  = $deviceType;
        $params['account']      = $account;
        $params['message_type'] = $message->getType();
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        return $this->api('push/single_account', $params);
    }


    /**
     * 使用默认设置推送消息给单个ios版账户
     */
    public function pushAccountIOS($content, $account, $environment)
    {
        $message = new MessageIOS();
        $message->setAlert($content);

        return $this->pushSingleAccount(0, $account, $message, $environment);
    }


    /**
     * 使用默认设置推送消息给所有设备android版
     *
     * @param string $title
     * @param string $content
     *
     * @return mixed
     * @throws Exception
     */
    public function pushAllAndroid($title, $content)
    {
        $message = new MessageAndroid();
        $message->setTitle($title);
        $message->setContent($content);
        $message->setType(MessageAndroid::TYPE_NOTIFICATION);
        $message->setStyle(new Style(0, 1, 1, 1, 0));
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_ACTIVITY);
        $message->setAction($action);

        return $this->pushAllDevices(0, $message);
    }


    /**
     * 推送消息给APP所有设备
     *
     * @param int                       $deviceType
     * @param MessageAndroid|MessageIOS $message
     * @param int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushAllDevices($deviceType, $message, $environment = 0)
    {
        $this->validateDeviceType($deviceType);
        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        $params['send_time']   = $message->getSendTime();
        if ($message instanceof MessageAndroid) {
            $params['multi_pkg'] = $message->getMultiPkg();
        }
        $params['device_type']  = $deviceType;
        $params['message_type'] = $message->getType();
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        if ( ! is_null($message->getLoopInterval()) && $message->getLoopInterval() > 0 && ! is_null($message->getLoopTimes()) && $message->getLoopTimes() > 0) {
            $params['loop_interval'] = $message->getLoopInterval();
            $params['loop_times']    = $message->getLoopTimes();
        }

        return $this->api('push/all_device', $params);
    }


    /**
     * 使用默认设置推送消息给所有设备ios版
     *
     * @param string $content
     * @param        $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function pushAllIOS($content, $environment)
    {
        $message = new MessageIOS();
        $message->setAlert($content);

        return $this->pushAllDevices(0, $message, $environment);
    }


    /**
     * 使用默认设置推送消息给标签选中设备android版
     *
     * @param $title
     * @param $content
     * @param $tag
     *
     * @return mixed
     * @throws Exception
     */
    public function pushTagAndroid($title, $content, $tag)
    {
        $message = new MessageAndroid();
        $message->setTitle($title);
        $message->setContent($content);
        $message->setType(MessageAndroid::TYPE_NOTIFICATION);
        $message->setStyle(new Style(0, 1, 1, 1, 0));
        $action = new ClickAction();
        $action->setActionType(ClickAction::TYPE_ACTIVITY);
        $message->setAction($action);

        return $this->pushTags(0, array( 0 => $tag ), 'OR', $message);
    }


    /**
     * 推送消息给指定tags的设备
     *
     * @param int                       $deviceType
     * @param array                     $tags
     * @param string                    $tagsOp
     * @param MessageAndroid|MessageIOS $message
     * @param int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushTags($deviceType, array $tags, $tagsOp, $message, $environment = 0)
    {
        $this->validateDeviceType($deviceType);
        $this->validateTagsOp($tagsOp);
        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        if (count($tags) == 1) {
            $tagsOp = 'OR';
        }

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        $params['send_time']   = $message->getSendTime();
        if ($message instanceof MessageAndroid) {
            $params['multi_pkg'] = $message->getMultiPkg();
        }
        $params['device_type']  = $deviceType;
        $params['message_type'] = $message->getType();
        $params['tags_list']    = json_encode($tags);
        $params['tags_op']      = $tagsOp;
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        if ( ! is_null($message->getLoopInterval()) && $message->getLoopInterval() > 0 && ! is_null($message->getLoopTimes()) && $message->getLoopTimes() > 0) {
            $params['loop_interval'] = $message->getLoopInterval();
            $params['loop_times']    = $message->getLoopTimes();
        }

        return $this->api('push/tags_device', $params);
    }


    /**
     * 使用默认设置推送消息给标签选中设备ios版
     *
     * @param $content
     * @param $tag
     * @param $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function pushTagIOS($content, $tag, $environment)
    {
        $message = new MessageIOS();
        $message->setAlert($content);

        return $this->pushTags(0, array( 0 => $tag ), 'OR', $message, $environment);
    }


    /**
     * 推送消息给多个账户
     *
     * @param                           $deviceType
     * @param array                     $accounts
     * @param MessageAndroid|MessageIOS $message
     * @param int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushAccounts($deviceType, $accounts, $message, $environment = 0)
    {
        $this->validateDeviceType($deviceType);
        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        if ($message instanceof MessageAndroid) {
            $params['multi_pkg'] = $message->getMultiPkg();
        }
        $params['device_type']  = $deviceType;
        $params['account_list'] = json_encode($accounts);
        $params['message_type'] = $message->getType();
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        return $this->api('push/account_list', $params);
    }


    /**
     * 创建批量推送任务
     *
     * @param MessageAndroid|MessageIOS $message
     * @param int                       $environment
     *
     * @return mixed
     * @throws Exception
     */
    public function  createMultiplePush($message, $environment = 0)
    {
        $this->validateMessage($message);
        $this->validateMessageType($message);
        $this->validateEnvironment($message, $environment);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['expire_time'] = $message->getExpireTime();
        if ($message instanceof MessageAndroid) {
            $params['multi_pkg'] = $message->getMultiPkg();
        }
        $params['message_type'] = $message->getType();
        $params['message']      = $message->toJson();
        $params['timestamp']    = time();
        $params['environment']  = $environment;

        return $this->api('push/create_multipush', $params);
    }


    /**
     * 按帐号大批量推送
     *
     * @param int   $pushId
     * @param array $accounts
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushAccountMass($pushId, array $accounts)
    {
        $params                 = array();
        $params['access_id']    = $this->accessId;
        $params['push_id']      = intval($pushId);
        $params['account_list'] = json_encode($accounts);
        $params['timestamp']    = time();

        return $this->api('push/account_list_multiple', $params);
    }


    /**
     * 按Token大批量推送
     *
     * @param       $pushId
     * @param array $devices
     *
     * @return mixed
     * @throws Exception
     */
    public function  pushDeviceMass($pushId, $devices)
    {
        $pushId = intval($pushId);

        $params                = array();
        $params['access_id']   = $this->accessId;
        $params['push_id']     = $pushId;
        $params['device_list'] = json_encode($devices);
        $params['timestamp']   = time();

        return $this->api('push/device_list_multiple', $params);
    }


    /**
     * 查询消息推送状态
     *
     * @param array $pushIds
     *
     * @return mixed
     * @throws Exception
     */
    public function  queryPushStatus(array $pushIds)
    {
        $ids = array();

        foreach ($pushIds as $pushId) {
            $ids[] = array( 'push_id' => $pushId );
        }

        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['push_ids']  = json_encode($ids);
        $params['timestamp'] = time();

        return $this->api('push/get_msg_status', $params);
    }


    /**
     * 查询应用覆盖的设备数
     *
     * @return mixed
     */
    public function  queryDeviceCount()
    {
        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['timestamp'] = time();

        return $this->api('application/get_app_device_num', $params);
    }


    /**
     * 查询应用标签
     *
     * @param int $start
     * @param int $limit
     *
     * @return mixed
     * @throws Exception
     */
    public function  queryTags($start = 0, $limit = 100)
    {
        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['start']     = $start;
        $params['limit']     = $limit;
        $params['timestamp'] = time();

        return $this->api('tags/query_app_tags', $params);
    }


    /**
     * 查询标签下token数量
     *
     * @param string $tag
     *
     * @return mixed
     * @throws Exception
     */
    public function  queryTagTokenCount($tag)
    {
        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['tag']       = $tag;
        $params['timestamp'] = time();

        return $this->api('tags/query_tag_token_num', $params);
    }


    /**
     * 查询token的标签
     *
     * @param string $deviceToken
     *
     * @return mixed
     * @throws Exception
     */
    public function  queryTokenTags($deviceToken)
    {
        $params                 = array();
        $params['access_id']    = $this->accessId;
        $params['device_token'] = $deviceToken;
        $params['timestamp']    = time();

        return $this->api('tags/query_token_tags', $params);
    }


    /**
     * 取消定时发送
     *
     * @param string $pushId
     *
     * @return mixed
     * @throws Exception
     */
    public function  cancelTimingPush($pushId)
    {
        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['push_id']   = $pushId;
        $params['timestamp'] = time();

        return $this->api('push/cancel_timing_task', $params);
    }


    /**
     * @param array $tagTokenPairs
     *
     * @return mixed
     * @throws Exception
     */
    public function batchSetTag(array $tagTokenPairs)
    {
        $params                   = array();
        $params['access_id']      = $this->accessId;
        $params['timestamp']      = time();
        $params['tag_token_list'] = json_encode($this->getTokensByPairs($tagTokenPairs));

        return $this->api('tags/batch_set', $params);
    }


    /**
     * @param $token
     *
     * @return bool
     */
    private function isTokenValid($token)
    {
        if ($this->accessId >= self::IOS_MIN_ID) {
            return strlen($token) == 64;
        } else {
            return ( strlen($token) == 40 || strlen($token) == 64 );
        }
    }


    /**
     * @param array $tagTokenPairs
     *
     * @return mixed
     * @throws Exception
     */
    public function batchDeleteTag(array $tagTokenPairs)
    {
        $params                   = array();
        $params['access_id']      = $this->accessId;
        $params['timestamp']      = time();
        $params['tag_token_list'] = json_encode($this->getTokensByPairs($tagTokenPairs));

        return $this->api('tags/batch_del', $params);
    }


    /**
     * @param string $deviceToken
     *
     * @return mixed
     * @throws Exception
     */
    public function queryInfoOfToken($deviceToken)
    {
        $params                 = array();
        $params['access_id']    = $this->accessId;
        $params['device_token'] = $deviceToken;
        $params['timestamp']    = time();

        return $this->api('application/get_app_token_info', $params);
    }


    /**
     * @param string $account
     *
     * @return mixed
     * @throws Exception
     */
    public function queryTokensOfAccount($account)
    {
        $params              = array();
        $params['access_id'] = $this->accessId;
        $params['account']   = $account;
        $params['timestamp'] = time();

        return $this->api('application/get_app_account_tokens', $params);
    }


    /**
     * @param array $tagTokenPairs
     *
     * @return array
     * @throws Exception
     */
    protected function getTokensByPairs(array $tagTokenPairs)
    {
        foreach ($tagTokenPairs as $pair) {
            if ( ! ( $pair instanceof TagTokenPair )) {
                throw new Exception('tag-token pair type error', -1);
            }
            if ( ! $this->isTokenValid($pair->token)) {
                throw new Exception(sprintf("invalid token %s", $pair->token), -1);
            }
        }

        $tagTokens = array();

        foreach ($tagTokenPairs as $pair) {
            array_push($tagTokens, array( $pair->tag, $pair->token ));
        }

        return $tagTokens;
    }

}