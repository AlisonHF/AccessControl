<?php

    namespace src\Model;

    use src\Config\Database;

    class User {
        private $pdo;

        public function __construct()
        {
            $this->pdo = Database::getConnection();
        }

        public function addUser($name, $lastname, $email, $password)
        {
            $query = 'SELECT email from `user` where email = :email';

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch();

            if (empty($user))
            {
                $query = "INSERT INTO `user`(name, last_name, email, password_hash) values(:name, :last_name, :email, :pass)";

                $stmt = $this->pdo->prepare($query);

                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':last_name', $lastname);
                $stmt->bindValue(':email', $email);

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt->bindValue(':pass', $hash);

                $stmt->execute();

                return true;
            }
            else
            {
                return false;
            }
        }

        public function login($email, $password)
        {
            $query = 'SELECT email, password_hash from `user` where email = :email';

            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($email === $user['email'] && password_verify($password, $user['password_hash']))
            {
                return true;
            }

            return false;
        }
    }
?>