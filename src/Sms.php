<?php
/*
 * @Author: PHP老赵
 * @Date: 2022-06-28 15:20:47
 * @LastEditTime: 2022-09-20 17:20:16
 */

/**
 * 短信
 */
namespace zhou\src;

class Sms
{
    protected $CorpID = '';
    protected $Pwd = '';
    protected $Mobile;
    protected $Content;
    protected $SendTime = null;
    protected $Sms = 1;
    public function __construct($arr = [])
    {
        foreach ($arr as $key => $value) {
            $this->setOption($key, $value);
        }
    }

    /**
     * 判断这个$key 是不是我的成员属性，如果是，则设置
     *
     * @param [type] $key
     * @param [type] $value
     * @return void
     */
    protected function setOption($key, $value)
    {
        //得到所有的成员属性
        $keys = array_keys(get_class_vars(__CLASS__));
        if (in_array($key, $keys)) {
            $this->$key = $value;
        }
    }
    /*发送短信*/
    public function set_phone()
    {
        header("Content-type: text/html; charset=utf-8");
        date_default_timezone_set('PRC'); //设置默认时区为北京时间
        $url = "http://api.ksd106.com:8088/WS/BatchSend2.aspx?";
//        $url = "https://mb345.com/ws/BatchSend2.aspx?";
        $ContentS = rawurlencode(mb_convert_encoding($this->Content, "gb2312", "utf-8")); //短信内容做GB2312转码处理
        $curpost = "CorpID=" . $this->CorpID . "&Pwd=" . $this->Pwd . "&Mobile=" . $this->Mobile . "&Content=" . $ContentS . "&SendTime=" . $this->SendTime;
        if ($this->Sms == 1) {
            //GET方式请求
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 -https
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($ch, CURLOPT_URL, $url . $curpost);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $result = curl_exec($ch);
            curl_close($ch);
            // $result = file_get_contents($url.$curpost);
        } else if ($this->Sms == 2) {
            //POST方式请求
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查 -https
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curpost);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }
}
