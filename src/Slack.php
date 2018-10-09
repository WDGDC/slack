<?php

namespace App;

class Slack 
{

    

    public static function _403($message = 'error') {
        header('HTTP/1.0 403 Forbidden');
        die($message);
    }

    public static function config() {
        
        static $config = null;

        if ($config) {
            return $config;
        }

        if (!file_exists('../config.json')) {
            _403();
        }

        $config = json_decode(file_get_contents('../config.json'), true);

        return $config;
    }

    public static function init() {

        $config = self::config();

        if (php_sapi_name() === 'cli') {
            $token = true;
        } else {
            $token = (isset($_POST['token']) && $_POST['token'] === $config['token']);
        }

        if (!$token) {
            _403('Token mismatch');
        }
    }

}

function _403($message = 'error') {
    Slack::_403($message);
}

