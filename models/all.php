<?php
//12
require_once "connection/Connection.php";
class all {
    public static function getCategories(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT C.id, C.description FROM categories AS C");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Categorias encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categorias no encontradas", "payload" => "");
        }
        return $response;

    }
    public static function getProfiles(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT P.id, P.description FROM profiles AS P");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Perfiles encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Perfiles no encontradas", "payload" => "");
        }
        return $response;

    }
    public static function getClients(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT E.id, E.description FROM clients AS E");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Categorias encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categorias no encontradas", "payload" => "");
        }
        return $response;
    }


}