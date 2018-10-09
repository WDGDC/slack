<?php

namespace App;

class Slack 
{

    protected static $message;
    protected static $attachments = [];

    public static function init($verifyToken = true) {

        $config = Config::get();

        if (php_sapi_name() === 'cli') {
            $token = true;
        } else {
            $token = (isset($_POST['token']) && $_POST['token'] === $config['token']);
        }

        if ($verifyToken && !$token) {
            _403('Token mismatch');
        }
    }

    public static function sendMessage($text = false) {
        header('Content-Type: application/json');

        $message = self::$message ?: new \stdClass();
        if ($text) {
            $message->text = $text;
        }

        if (self::$attachments) {
            $message->attachments = self::$attachments;
        }

        echo json_encode($message);

    }

    public static function attachment($text, $title = false) {

    /*
    {
        "fallback": "Required plain-text summary of the attachment.",
        "color": "#36a64f",
        "pretext": "Optional text that appears above the attachment block",
        "author_name": "Bobby Tables",
        "author_link": "http://flickr.com/bobby/",
        "author_icon": "http://flickr.com/icons/bobby.jpg",
        "title": "Slack API Documentation",
        "title_link": "https://api.slack.com/",
        "text": "Optional text that appears within the attachment",
        "fields": [
            {
                "title": "Priority",
                "value": "High",
                "short": false
            }
        ],
        "image_url": "http://my-website.com/path/to/image.jpg",
        "thumb_url": "http://example.com/path/to/thumb.png",
        "footer": "Slack API",
        "footer_icon": "https://platform.slack-edge.com/img/default_application_icon.png",
        "ts": 123456789
    }
    */
        
        $attachment = new \stdClass();
        $attachment->text = $text;
        if ($title) {
            $attachment->title = $title;
        }
        
        self::$attachments[] = $attachment;

    }

}
