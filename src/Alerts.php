<?php 

namespace App;

use Akamai\Open\EdgeGrid\Client;

class Alerts 
{

    protected static function client() {

        $config = Slack::config();

        $client_secret  = $config['akamai']['client_secret'];
        $host           = $config['akamai']['host'];
        $access_token   = $config['akamai']['access_token'];
        $client_token   = $config['akamai']['client_token'];

        $client = new Client([
            'base_uri' => 'https://' . $host
        ]);

        $client->setAuth($client_token, $client_secret, $access_token);

        return $client;
    }

    protected static function types() {
        // Get Types
        $response = self::client()->get('/alerts/v2/alert-definitions');

        $data = json_decode($response->getBody());

        if (!$data) {
            die('error');
        }

        echo '--- Alert Types ---' . "\n";

        foreach ($data->data as $row) {
            echo $row->definitionId . ' - ' . $row->fields->name . "\n";
        }
    }

    protected static function active($type = false) {
        // All Active
        if ($type) {
            return self::activeByType($type);
        }

        $response = self::client()->get('/alerts/v2/alert-firings/active');

        $data = json_decode($response->getBody());

        if (!$data) {
            die('error');
        }

        echo '--- Active Alerts ---' . "\n";

        // echo $response->getBody();
        foreach ($data->data as $row) {
            echo $row->firingId . ' - ' . $row->name . "\n";
        }

    }

    protected static function activeByType($type) {

        $response = self::client()->get("/alerts/v2/alert-definitions/{$type}/alert-firings");

        $data = json_decode($response->getBody());

        if (!$data) {
            die('error');
        }

        echo "--- Alerts: {$type} ---\n";

        echo $response->getBody();
        foreach ($data->data as $row) {
        
            $str = '  ';
            $str .= $row->firingId . ' - started: ' . $row->startTime;

             if ($row->endTime) {
                $str .= " - cleared: {$row->endTime}";
            }

            echo $str . "\n";
        }
    }

    public static function error($msg = 'Error') {
        echo $msg . "\n";
        exit(1);
    }

    public static function init() {
        
        if (isset($_POST['text'])) {
            $argv = explode(' ', $_POST['text']);
            array_unshift($argv, true);
        }

        if (!isset($argv[1])) {
            echo "Must select action.\n";

            echo " - types \n";
            echo " - active [type]\n";

            exit(1);
        }

        switch($argv[1]) {
            case 'types';
                self::types();
                break;
            case 'active';
                $type = isset($argv[2]) ? $argv[2] : false;
                self::active($type);
                break;
            default;
                self::error('Not valid action.');
                break;
        }

    }
}



