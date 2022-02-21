<?php

namespace yii\services;

use paragraph1\phpFCM\Client;

class Push {
    protected $url = 'https://fcm.googleapis.com/fcm/send';
    protected $key = 'AIzaSyA-gm-uERY-6xfIhwDI6ZaisCkRbryGlZc';

    protected $message = array();

    public function setNotification(array $args) {
        $this->message['notification'] = array(
            "title"=>"Testoo Notification",
            "body"=>"Notification is delivered!",
            "sound"=>"call",
            "icon"=> "ic_app_icon"
        );

        if ($args) {
            foreach ($args as $k => $v) {
                $this->message['notification'][$k] = $v;
            }
        }

        return true;
    }

    public function setData(array $args) {
        if ($args) {
            foreach ($args as $k => $v) {
                $this->message['data'][$k] = $v;
            }
        }

        return true;
    }

    public function setDeviceToken($token) {
        $this->message['to'] = $token;
    }

    public function setPriority($priority) {
        $this->message['priority'] = $priority;
    }

    public function send() {
        ob_start();

        $client = new Client();
        $client->setApiKey($this->key);
        $client->injectHttpClient(new \GuzzleHttp\Client());

        return $client->guzzleClient->post(
            $this->url,
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', $this->key),
                    'Content-Type' => 'application/json'
                ],
                'body' => json_encode($this->message)
            ]
        );
    }
}