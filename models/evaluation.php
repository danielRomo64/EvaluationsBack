<?php

require_once("connection/Connection.php");

class evaluation {

    public static function newQuestion($category_id,$state_type,$title,$description,$minimum,$maximum,$type){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO questions(category_id,  state_type, title, description, minimum, maximum, type) 
        VALUES ('$category_id', '$state_type', '$title', '$description','$minimum','$maximum','$type')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Pregunta Creada exitosamente.", "payload" => "") ;
        }else{
            return array("code" => 0, "message" => "Error al crear pregunta", "payload" => "");            
        }

    }
    public static function newPregunta($categoria, $user_pregunta, $user_inial, $user_final, $titulo){
        
        $connection = new Connection();
        $db = $connection->connect();

        if ($db){

            /* $query = "INSERT INTO questions(category_id, range_id, state_type, title, description) 
            VALUES ('$category_id', '$range_id', '$state_type', '$title', '$description')"; */

            $query = "INSERT INTO preguntas(categoria_id, pregunta, rangoI, rangoF, state_type, titulo) 
            VALUES ('$categoria', '$user_pregunta', '$user_inial', '$user_final', 1, '$titulo')";                  

            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Pregunta creada exitosamente", "payload" => "") ;
            }else{
                return array("code" => 0, "message" => "Error al crear pregunta", "payload" => "");            
            }            
        }else{
            return array("code" => -1, "message" => "Problemas con la BD", "payload" => "");              
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

    public static function deleteQuestion($id){

        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        if ($db){
            if(!empty($id)){

                $query = "UPDATE questions SET state_type = Not state_type WHERE id = $id";
                $statement = $db->prepare($query);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    return array("code" => 1, "message" => "Pregunta ha sido procesada exitosamente", "payload" => "") ;
                }else{
                    return array("code" => 0, "message" => "Pregunta no pudo ser desactivada", "payload" => "");
                } 
            }else {
                echo json_encode( array("code" => 2, "message" => "Datos Erroneos o faltantes", "payload" => ""));
            }           
        }else{
            return array("code" => -1, "message" => "Problemas con la BD", "payload" => "");
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

    public static function questionUser($question_id,$user_id,$evaluator_id,$evaluated_range,$feedback){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO `evaluation_logs` (`question_id`, `user_id`, `evaluator_id`, `evaluated_range`, `feedback`, `date`) VALUES ( '$question_id', '$user_id', '$evaluator_id', '$evaluated_range', '$feedback', CURTIME());";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Valoración Creada", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Error al crear Valoración", "payload" => "");
    }

    public static function selectAllQuestion(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT Q.id, C.description AS categoria, 
                            if(Q.state_type != 1, 'inactivo', 'activo') AS states, Q.title, Q.description, 
                            if(Q.minimum = 0, if(Q.maximum = 0, 'Cualitativa', 'Cuantitativa'), 'Cuantitativa') AS type, 
                            Q.maximum, Q.minimum 
                            FROM questions AS Q 
                            INNER JOIN categories AS C ON C.id = Q.category_id;");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'categoria' => $row['categoria'],
                    'states' => $row['states'],
                    'title' => $row['title'],
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum'],                    
                    'description' => $row['description'],
                    'type' => $row['type']

                ];
            }
            $response = array("code" => 1, "message" => "Preguntas por categoria encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categoria no encontradas", "payload" => []);
        }
        return $response;
    }

}