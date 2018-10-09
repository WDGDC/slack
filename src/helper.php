<?php


if (!function_exists('_403')) {
    function _403($message = 'error') {
        header('HTTP/1.0 403 Forbidden');
        die($message);
    }
}
