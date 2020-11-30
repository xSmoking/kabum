<?php

class Database
{
    private static $host = "localhost";
    private static $db_name = "kabum";
    private static $user = "root";
    private static $pass = "root";

    public static function connection()
    {
        try
        {
            $conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$user, self::$pass);
            $conn->exec("set names utf8");
        }
        catch (PDOException $exception)
        {
            echo "Connection error: " . $exception->getMessage();
        }

        return $conn;
    }
}
