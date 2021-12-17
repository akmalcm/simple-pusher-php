<?php

require 'vendor/autoload.php';
require_once('config.php'); // config for pusher credential

class PusherClass {
    private $pusher;
    private $username;
    private $sessionid;

    // function constructor
    public function __construct() {
        $options = array(
            'cluster' => $GLOBALS['app_cluster'],
            'useTLS' => true
        );

        $this->pusher = new Pusher\Pusher(
            $GLOBALS['app_key'],
            $GLOBALS['app_secret'],
            $GLOBALS['app_id'],
            $options
        );

        $this->username = $_SESSION['username'];
        $this->sessionid = session_id();

        $data['message'] = $this->username . " has entered the room.";
        $data['user_id'] = session_id();
        $this->pusher->trigger('presence-my-channel', 'my-event', $data);
    }

    function auth() {
        if (isset($_POST['socket_id'])) {
            die($this->pusher->presenceAuth("presence-my-channel", $_POST['socket_id'], $this->sessionid, array('username' => $this->username)));
        }
    }

    function trigger() {
        $data['message'] = $_POST['message'];
        $data['user_id'] = session_id();
        $this->pusher->trigger('presence-my-channel', 'my-event', $data);
    }
}
