<?php namespace Tencent\XGPush\Models;

class ParamsBase
{

    /**
     * @var array 当前传入的参数列表
     */
    public $params = array();


    public function __construct($params)
    {
        $this->params = $params;
    }


    public function set($k, $v)
    {
        if ( ! isset( $k ) || ! isset( $v )) {
            return;
        }
        $this->params[$k] = $v;
    }


    /**
     * 根据实例化传入的参数生成签名
     */
    public function generateSign($method, $url, $secretKey)
    {
        //将参数进行升序排序
        $paramStr = '';
        $method   = strtoupper($method);
        $url_arr  = parse_url($url);
        if (isset( $url_arr['host'] ) && isset( $url_arr['path'] )) {
            $url = $url_arr['host'] . $url_arr['path'];
        }
        if ( ! empty( $this->params )) {
            ksort($this->params);
            foreach ($this->params as $key => $value) {
                $paramStr .= $key . '=' . $value;
            }
        }

        //print $method.$url.$param_str.$secret_key."\n";
        return md5($method . $url . $paramStr . $secretKey);
    }
}