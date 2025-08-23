<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use src\Controllers\UserController;

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if ($uri == '/' || $uri == '/login')
    {
        (new UserController)->index();
    }

    else if ($uri == '/register')
    {
        (new UserController)->register();
    }

    else if ($uri == '/home')
    {
        (new UserController)->home();
    }

    else if ($uri == '/logoff')
    {
        (new UserController)->logoff();
    }

    else if ($uri == '/edit')
    {
        (new UserController)->edit();
    }

?>