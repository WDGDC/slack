<?php

namespace App;


class Parser 
{

    public static function parse($template, $data) {
        $m = new \Mustache_Engine;
        return $m->render($template, $data);
    }
}
