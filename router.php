<?php
session_start();

$request = $_SERVER['REQUEST_URI']; // if your url look like this : http://localhost/application_name
//$request = $_SERVER['REQUEST_URI']; // if your url look like this : http://localhost/

switch ($request) {
    case '/':
    case '':
        require __DIR__ . '/views/index.php';
        break;
    case '/post':
        if (isset($_POST['submitUsername'])) {
            $_SESSION['username'] = $_POST['username'];
            header('Location: /');
        } else if (isset($_POST['submitMessage'])) {

            require __DIR__ . '/pusher.php';
            $instance = new PusherClass();
            $instance->trigger();
            /* $ret = call_user_func(array($instance, 'trigger'));

            var_dump($ret);
            if (empty($ret)) {
                throw new Exception("empty $ret.");
            } */
        }
        
        break;
    case '/pusher/auth':
        require __DIR__ . '/pusher.php';
        $instance = new PusherClass();

        $ret = call_user_func(array($instance, 'auth'));

        if (empty($ret)) {
            throw new Exception("empty $ret.");
        }
        break;
    default:
        http_response_code(404);
        break;
}
