<?php namespace Tencent\XGPush\Models;

class TagTokenPair
{

    public $tag;

    public $token;


    public function __construct($tag, $token)
    {
        $this->tag   = strval($tag);
        $this->token = strval($token);
    }
}
