<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use src\Controllers\UserController;

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    switch($uri)
    {
        case '/':
            (new UserController)->index();
            
            break;

        case '/login':
            (new UserController)->index();

            break;
        
        case '/register':
            (new UserController)->register();

            break;

        case '/home':
            (new UserController)->home();

            break;
        
        case '/logoff':
            (new UserController)->logoff();

            break;

        case '/edit':
            (new UserController)->edit();

            break;

        case '/forgot_password':
            (new UserController)->forgotPassword();

            break;

        case '/forgot_password/recovery_cod':
            (new UserController)->recoveryCod();

            break;

        case '/forgot_password/reset_password':
            (new UserController)->resetPassword();

            break;
        
    }
?>