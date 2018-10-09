<?php

namespace App;

class Config 
{

    public static function get() {

        static $config = null;

        if ($config) {
            return $config;
        }

        $file = __DIR__ . '/../config.json';

        if (!file_exists($file)) {
            _403();
        }

        $config = json_decode(file_get_contents($file), true);

        if (!$config) {
            _403('Bad Config');
        }

        return $config;
    }

}

