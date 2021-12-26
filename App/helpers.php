<?php

use Phpfastcache\Helper\Psr16Adapter;

if(!function_exists('cache')){
    function cache(){
        return ccache::get();
    }
    class ccache
    {
        private static $instance = null;
        public static function get()
        {if (!self::$instance) { self::$instance = new Psr16Adapter('Files'); } return self::$instance;}
    }
}
if(!function_exists('toJson')){
    function toJson($data){
        echo json_encode($data);
        return null;
    }
}
if(!function_exists('env')){
    function env($key, $default = null){
        return (isset($_ENV[$key])) ? $_ENV[$key] : $default;
    }
}