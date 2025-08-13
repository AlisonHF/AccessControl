<?php

    namespace src\Controllers;

    use src\Model\User;

    class UserController
    {
        private $path_views =  __DIR__ . '/../../views/';
        private $user;

        public function __construct()
        {
            $this->user = new User();
        }

        public function index()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $user = $_POST['email'];
                $password = $_POST['password'];
            }
            else
            {
                require_once($this->path_views . 'login.phtml');
            }
            
        }

        public function register()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $name = $_POST['name'];
                $last_name = $_POST['last-name'];   
                $email = $_POST['email'];
                $password = $_POST['password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
                
                $this->user->addUser($name, $last_name, $email, $hash);
            }

            require_once($this->path_views . 'register.phtml');
        }

        public function home()
        {
            require_once($this->path_views . 'home.phtml');
        }
    }

?>