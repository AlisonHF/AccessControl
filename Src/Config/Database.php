<?php

    namespace src\Config;

    class Database
    {
        private static $instance = null;

        private $connection;

        private function __construct()
        {
            $host = 'localhost';

            $db_name = 'login';

            $user = 'root';

            $pass = '';

            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

            try
            {
                $this->connection = new \PDO($dsn, $user, $pass);
            }

            catch(\PDOException $e)
            {
                throw new \PDOException($e->getMessage(), $e->getCode());
            }

        }

        public static function getConnection()
            {
                if (self::$instance === null)
                {
                    self::$instance = new Database();
                }

                return self::$instance->connection;
            }
    }

?>