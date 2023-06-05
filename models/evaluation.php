<?php

require_once "connection/Connection.php";

class evaluation {
    public static function newQuestion($category_id,$range_id,$state_type,$title,$description){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO questions(category_id, range_id, state_type, title, description) VALUES ('$category_id', '$range_id', '$state_type', '$title', '$description')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "pregunta Creada", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Error al crear pregunta", "payload" => "");

    }
}