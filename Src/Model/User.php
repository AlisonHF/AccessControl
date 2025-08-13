<?php

    namespace src\Model;

    use src\Config\Database;

    class User {
        private $pdo;

        public function __construct()
        {
            $this->pdo = Database::getConnection();
        }

        public function addUser($name, $last, $email, $pass)
        {
            $query = "INSERT INTO `user`(name, last_name, email, password_hash) values(:name, :last_name, :email, :pass)";

            $stmt = $this->pdo->prepare($query);

            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':last_name', $last);
            $stmt->bindValue(':email', $email);

            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt->bindValue(':pass', $hash);

            $execute = $stmt->execute();

            return $execute;
        }
    }
?>