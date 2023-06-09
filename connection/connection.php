<?php

/*class Connection
{
    public function connect()
    {
        $opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
        try {
            $db = new PDO('mysql:host=localhost;dbname=prue', 'root', '', $opciones);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
            return null;
        }
    }

}*/




//primera
class Connection
{
    public function connect()
    {
        $opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
        try {
            $db = new PDO('mysql:host=localhost;dbname=evaluations', 'root', '', $opciones);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {
            echo 'Falló la conexión: ' . $e->getMessage();
            return null;
        }
    }

}










/*
$connection = new Connection();

$db = $connection->connect();

if ($db) {
    echo "Conexión exitosa ";
} else {
    echo "Error al conectar ";
}*/



/*
class Connection
{
    public function connect()
    {
        $mysqli = new mysqli('localhost', 'root', '', 'evaluations');

        if ($mysqli->connect_error) {
            echo 'Falló la conexión: ' . $mysqli->connect_error;
            return null;
        }

        $mysqli->set_charset("utf8");
        return $mysqli;
    }
}

$connection = new Connection();
$db = $connection->connect();

if ($db) {
    echo "Conexión exitosa";
} else {
    echo "Error al conectar";
}
*/