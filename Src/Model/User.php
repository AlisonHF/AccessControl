<?php

    namespace src\Model;

    use src\Config\Database;

    class User {
        private $pdo;

        public function __construct()
        {
            $this->pdo = Database::getConnection();
        }

        public function register($name, $last_name, $email, $password, $password_repeat) // Registrar usuário
        {

            if ($password != $password_repeat)
            {
                return [false, 'password_repeat_distinct'];
            }

            $stmt = $this->pdo->prepare('SELECT email from `user` where email = ?');

            $stmt->execute([$email]);

            $user = $stmt->fetch();

            if (empty($user))
            {
                $stmt = $this->pdo->prepare("INSERT INTO `user`(name, last_name, email, password_hash) values(?, ?, ?, ?)");

                $hash = password_hash($password, PASSWORD_DEFAULT);

                $stmt->execute([$name, $last_name, $email, $hash]);

                return [true];
            }
            
            else
            {
                return [false, 'existing_user'];
            }
        }

        public function login($email, $password) // Realizar login
        {
            $stmt = $this->pdo->prepare('SELECT email, password_hash from `user` where email = ?');

            $stmt->execute([$email]);

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($email === $user['email'] && password_verify($password, $user['password_hash']))
            {
                return true;
            }

            return false;
        }

        public function getUserInfo($email) // Pegar informações do usuário 
        {
            $stmt = $this->pdo->prepare('SELECT id, name, last_name, email, created_in from `user` where email = ?');

            $stmt->execute([$email]);

            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!empty($user))
            {
                $user_info = [
                    'id' => $user['id'],

                    'name' => $user['name'],

                    'last_name' => $user['last_name'],

                    'email' => $user['email'],

                    'created_in' => $user['created_in'],
                ];

                return $user_info;
            }

            return false;
        }

        public function edit($id, $name, $last_name, $email) // Editar cadastro usuário
        {
            $stmt = $this->pdo->prepare('SELECT id, email from `user` where email = ?');

            $stmt->execute([$email]);

            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (empty($result))
            {
                $stmt = $this->pdo->prepare('update `user` set name = ?, last_name = ?, email = ? where id = ?');

                $edit = $stmt->execute([$name, $last_name, $email, $id]);

                return $edit;
            }

            else
            {
                foreach($result as $key => $register){

                    if ($register['id'] == $id)
                    {
                        $stmt = $this->pdo->prepare('update `user` set name = ?, last_name = ? where id = ?');

                        $edit = $stmt->execute([$name, $last_name, $id]);

                        return $edit;
                    }
                };
                return false;
            }
        }

        public function getPassword($id) // Pegar hash da senha do usuário
        {
            $stmt = $this->pdo->prepare('select password_hash from `user` where id = ?');
            
            $stmt->execute([$id]);

            $password_hash = $stmt->fetch(\PDO::FETCH_NUM); 

            return $password_hash[0];
        }

        public function setPassword($id, $new_password) // Editar senha
        {
            $new_password = password_hash($new_password, PASSWORD_DEFAULT);

            $stmt = $this->pdo->prepare('update `user` set password_hash = ? where id = ?');

            $set = $stmt->execute([$new_password, $id]);

            return $set;
        }
    }
?>