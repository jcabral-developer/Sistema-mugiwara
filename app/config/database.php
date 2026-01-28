<?php

class Database
{

        private static $host = 'localhost';
        private static $db = 'mugiwara';
        private static $user = 'root';
        private static $pass = '';
        private static $charset = 'utf8mb4';

        public static function connect()
        {

                try {

                        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset;

                        $option = [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // errores como excepciones
                                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // arrays asociativos
                                PDO::ATTR_EMULATE_PREPARES => false,                  // consultas reales

                        ];

                        return new PDO($dsn, self::$user, self::$pass, $option);

                } catch (PDOException $e) {
                die("Error de conexion: " . $e->getMessage());
                }
        }

}