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

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $email = htmlspecialchars($_POST['email']);
                $password = $_POST['password'] ?? '';

                if (!$email || !$password)
                {
                    header('Location:/login?event=missing_field');
                }
                else {
                    $registration_exist = $this->user->login($email, $password);

                    if ($registration_exist === true)
                    {
                        header('Location:/home');
                    }
                    else
                    {
                        header('Location:/login?event=user_invalid');
                    }
                }
            }

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
                    header('Location:/register?error=true&type=missing_field');
                    
                }
                else
                {   
                    $register = $this->user->addUser($name, $last_name, $email, $password);

                    if ($register === true)
                    {
                        header('Location:/login?event=register');
                    }
                    else
                    {
                        header('Location:/register?error=true&type=user_exists');
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