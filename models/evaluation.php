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

    public static function updateEvaluation($id, $category_id, $range_id, $state_type, $title, $description)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($category_id) && !empty($range_id) && !empty($state_type) && !empty($title) && !empty($description) ){
            $query = "UPDATE `questions` SET `category_id` = '$category_id', `range_id` = '$range_id', `state_type` = '$state_type', `title` = '$title', `description` = '$description' WHERE `questions`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Pregunta Actualizada", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Pregunta no actualizado", "payload" => "");
        }
    }
    public static function questionCategory($category_id){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT Q.id, CONCAT(R.minimum,' - ',R.maximum) AS rang, Q.title, Q.description FROM questions AS Q  INNER JOIN ranges AS R ON Q.range_id = R.id WHERE Q.state_type = 1 AND Q.category_id ='$category_id';");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'rang' => $row['rang'],
                    'title' => $row['title'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Preguntas por categoria encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categoria no encontradas", "payload" => "");
        }
        return $response;
    }

    public static function deleteQuestion($id, $state_type)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($state_type)){
            $query = "UPDATE `questions` SET `state_type` = '$state_type' WHERE `questions`.`id` = '$id';";
            $statement = $db->prepare($query);
            $statement->execute();
        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Pregunta Eliminado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Pregunta no Eliminado", "payload" => "");
        }
    }

    public static function newCategories($description){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO categories(description) VALUES ('$description')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Categoria Creada", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Error al crear Categoria", "payload" => "");
    }
}