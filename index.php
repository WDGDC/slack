<?php

require_once 'vendor/autoload.php';

function _403($message = 'error') {
    header('HTTP/1.0 403 Forbidden');
    die($message);
}

if (!file_exists('config.json')) {
    _403();
}

$config = json_decode(file_get_contents('config.json'), true);

if (php_sapi_name() === 'cli') {
    $ping = isset($argv[1]) ? $argv[1] : false;
    $token = true;
} else {
    $ping = isset($_GET['ping']) ? $_GET['ping'] : false;
    $token = (isset($_POST['token']) && $_POST['token'] === $config['token']);
}

if (!$token) {
    _403('Token mismatch');
}

if (!$ping || !isset($config['ping'][$ping])) {
    _403('Ping site not found');
}

$curl = new Curl\Curl();
$cachebuster = time();


$url = $config['ping'][$ping];

// if cachebuster
$url .= "?$cachebuster";

$curl->get($url);

if ($curl->error) {
    echo $curl->error_code;
    exit;
}

$body = $curl->response;

echo $body;
