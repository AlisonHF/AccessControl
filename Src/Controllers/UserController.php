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
                        $user_info = $this->user->getUserInfo($email);

                        session_start();

                        $_SESSION['user'] = [
                            'id' => $user_info['id'],

                            'name' => $user_info['name'],

                            'last_name' => $user_info['last_name'],

                            'email' => $user_info['email'],

                            'created_in' => $user_info['created_in'],
                        ];

                        header('Location:/home');

                        exit;
                    }
                    else
                    {
                        header('Location:/login?event=user_invalid');
                        exit;
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
            session_start();

            if (isset($_SESSION['user']))
            {

                if (isset($_GET['register']))
                {
                    $register = $_GET['register'];

                    switch ($register)
                    {
                        case 'true':
                            $message_alert = 'Registro alterado com sucesso!';

                            break;
                    }
                }
                
                require_once($this->path_views . 'home.phtml');

            }

            require_once($this->path_views . 'login.phtml');
            
        }

        public function logoff()
        {
            session_start();

            session_destroy();

            require_once($this->path_views . 'login.phtml');
        }

        public function edit()
        {
            session_start();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') // Método POST
            {
                $id = htmlspecialchars($_POST['id']);

                $name = htmlspecialchars($_POST['name']);

                $last_name = htmlspecialchars($_POST['last_name']);   

                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

                if (!$id || !$name || !$last_name || !$email) // Se algum dos campos estiverem vazios
                {
                    header('Location:/edit?warning=missing_field_edit');

                    exit;
                }

                if (!($id == $_SESSION['user']['id'])) // Verifica se o ID foi alterado no envio do formulário
                {
                    header('Location:/edit?warning=invalid_id_edit');

                    exit;
                }

                $current_password = $_POST['current_password'];
                
                $new_password = $_POST['new_password'];
                
                if ($current_password && $new_password) // Se será alterado a senha
                {
                    $password_hash_current = $this->user->getPassword($id);

                    if (password_verify($current_password, $password_hash_current,))
                    {
                        $set = $this->user->setPassword($id, $new_password);
                        
                        if ($set)
                        {
                            header('Location:/edit?warning=success_edit');

                            exit;
                        }
                        
                        header('Location:/edit?warning=error_edit');

                        exit;

                    }

                    header('Location:/edit?warning=incorrect_password_edit');
                    
                    exit;
                }

                $edit = $this->user->edit($id, $name, $last_name, $email);

                if ($edit === false) // Se não foi possível editar o registro
                {
                    header('Location: /edit?warning=invalid_email_edit');

                    exit;
                }

                // Se foi editado o registro

                $_SESSION['user']['name'] = $name;

                $_SESSION['user']['last_name'] = $last_name;

                $_SESSION['user']['email'] = $email;

                header('Location: /home?register=true');

                exit;

            }

            else // Método GET
            {
                if (isset($_GET['warning']))
                {
                    $error = $_GET['warning'];

                    switch($error)
                    {
                        case 'missing_field_edit':
                            $message_alert = 'Preencha todos os campos antes de salvar!';

                            break;

                        case 'invalid_id_edit':
                            $message_alert = 'Ocorreu um erro, entre em contato conosco e apresente o código: 001';

                            break;

                        case 'invalid_email_edit':
                            $message_alert = 'E-mail já cadastrado, use outro!';

                            break;

                        case 'success_edit':
                            $message_alert = 'Usuário editado com sucesso!';

                            break;
                        
                        case 'error_edit':
                            $message_alert = 'Ocorreu um erro, entre em contato conosco e apresente o código: 002';

                            break;

                        case 'incorrect_password_edit':
                            $message_alert = 'Senha atual incorreta!';

                            break;
                    }
                }

                require_once($this->path_views . 'edit.phtml');

                exit;
            }           
        
        }

    }
?>