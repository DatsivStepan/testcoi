<?php

namespace App\Helpers;

use Ixudra\Curl\Facades\Curl;

/**
 * Class FunctionHelper
 * @package app\Helpers
 */
class FunctionHelper
{
    /**
     * @param $url
     * @param $data
     * @return bool|string
     */
    public static function post($url, $data)
    {
        try {
            return Curl::to($url)->withData($data)->asJson()->get();
        } catch (\Exception $e) {
            return false;
        }
    }
}