<?php

namespace App\Http\Tools;

class ParamTools
{
    private static $ignore_fields = ["_method", "_token", "profile"];

    public static function get_value(array $params, $key, $default = null) {
        return isset($params[$key]) && !empty($params[$key]) ? $params[$key] : $default;
    }
    
    public static function repair_params(array $params) {
        $ret = [];
        foreach ($params as $key => $value) {
            if (in_array($key, ParamTools::$ignore_fields)) {
                continue;
            }
            $ret[$key] = $value;
        }
        return $ret;
    }
}