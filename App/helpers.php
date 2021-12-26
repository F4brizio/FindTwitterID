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