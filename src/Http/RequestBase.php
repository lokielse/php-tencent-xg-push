<?php namespace Tencent\XGPush\Http;

use Tencent\XGPush\Exceptions\Exception;

class RequestBase
{

    const METHOD_GET = 'get';
    const METHOD_POST = 'post';


    /**
     * 发起一个HTTP请求
     *
     * @param        $url
     * @param array  $params
     * @param string $method
     * @param array  $options
     *
     * @return bool|mixed
     * @throws Exception
     */
    public static function exec($url, $params = array(), $method = self::METHOD_GET, $options = array())
    {
        $params = is_array($params) ? http_build_query($params) : $params;

        /**
         * 如果是get请求，直接将参数附在url后面
         */
        if ($method == self::METHOD_GET) {
            $url .= ( strpos($url, '?') === false ? '?' : '&' ) . $params;
        }

        /**
         * 默认配置
         */
        $curlOptions = array(
            CURLOPT_URL            => $url,  //请求url
            CURLOPT_HEADER         => false,  //不输出头信息
            CURLOPT_RETURNTRANSFER => true, //不输出返回数据
            CURLOPT_CONNECTTIMEOUT => 3 // 连接超时时间
        );

        /**
         * 配置post请求额外需要的配置项
         */
        if ($method == self::METHOD_POST) {
            $curlOptions[CURLOPT_POST]       = true;
            $curlOptions[CURLOPT_POSTFIELDS] = $params;
        }

        /**
         * 添加额外的配置
         */
        foreach ($options as $k => $v) {
            $curlOptions[$k] = $v;
        }

        try {
            $ch = curl_init();
            curl_setopt_array($ch, $curlOptions);
            $data = curl_exec($ch);

            if ($data === false) {
                throw new \Exception('CURL ERROR: ' . curl_error($ch));
            }

            curl_close($ch);

        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

        return $data;
    }
}