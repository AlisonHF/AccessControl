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
            require_once($this->path_views . 'login.phtml');
        }

        public function register()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $name = htmlspecialchars($_POST['name']);
                $last_name = htmlspecialchars($_POST['last_name']);   
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = $_POST['password'] ?? '';

                if (!$name || !$last_name || !$email || !$password)
                {
                    header('Location:/register?error=true');
                }
                else
                {   
                    $register = $this->user->addUser($name, $last_name, $email, $password);

                    if ($register === true)
                    {
                        header('Location:/register?error=false');
                    }
                    else
                    {
                        header('Location:/register?error=true');
                    }
                }
            }

            else
            {
                require_once($this->path_views . 'register.phtml');
            }
            
        }

        public function home()
        {
            require_once($this->path_views . 'home.phtml');
        }
        
    }

?>