<?php

namespace App\Http\AppClass;

use stdClass;

class API
{
    public const URL = "/api/tokensupport";



    /**
     * CheckUser By Apps name
     * @param Apps $appname
     * @param mixed $token
     *
     * @return void
     */
    public static function CheckUser(Apps $appname, $token)
    {
        $respons = self::_sendToken($appname->Urlwebsite  . self::URL, $token);
        $jsondata = json_decode($respons);
        if ($jsondata == false) {
            $jsondata = new stdClass();

            $jsondata->error = true;
        }

        return $jsondata;
    }


    /**
     * send post url api app
     * @param mixed $url
     * @param mixed $data
     *
     * @return void
     */
    private static function _sendToken($url, $token)
    {
        $data = [
            "token" => $token
        ];

        return  self::_POSTCURL($url, $data);
    }
    /**
     * send post url api app
     * @param mixed $url
     * @param mixed $data
     *
     * @return void
     */
    private static function _POSTCURL($url, $data)
    {
        $post = "";
        foreach ($data as $key => $value) {
            if ($post !== "")
                $post .= "&";
            $value = urlencode($value);
            $post .= " $key=$value";
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $post
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $curlbody = curl_exec($ch);
        curl_close($ch);
        return  $curlbody;
    }
}
