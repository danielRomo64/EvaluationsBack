<?php

    class Connection
    {
        public function connect()
        {
            $opciones = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];
            try {
                $db = new PDO('mysql:host=localhost;dbname=evaluacion', 'root', '', $opciones);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $db;
            } catch (PDOException $e) {
                //echo json_encode($res);
                return null;
            }
        }
    }
?>


