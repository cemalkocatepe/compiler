<?php

namespace Boyner\Compailer\DB;

class DBConnection
{
    private $database;

    public function __construct()
    {
        $this->database = include(__DIR__ . '/DBConfig.php');
    }

    public function connection(string $dbName = '')
    {
        $mysqli = mysqli_connect(
            $this->database[$dbName]['host'],
            $this->database[$dbName]['user'],
            $this->database[$dbName]['pass'],
            $this->database[$dbName]['db'],
        );

        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        return $mysqli;
    }
}
