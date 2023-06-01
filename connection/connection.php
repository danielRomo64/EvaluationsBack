<?php

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


