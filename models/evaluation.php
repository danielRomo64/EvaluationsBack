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

    public static function getCollaboratorsEvaluator($id_evaluator){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $validEvaluator = self::validEvaluator($id_evaluator);
        if (strpos(strtolower($validEvaluator['description']), 'eliza') !== false){
            $whereEvaluator = "";
        }else{
            $whereEvaluator = "U.id_user IN (SELECT R.id_user FROM user_relations AS R WHERE R.id_evaluator = '$id_evaluator' ) AND";
        }

        $query = $db->query("SELECT U.id_user, CONCAT(U.first_name,' ',U.last_name) AS name, U.user_registered , U.user_evaluation_date, C.description FROM user AS U INNER JOIN profiles AS P ON P.id = U.user_profile INNER JOIN user_relations AS T ON T.id_user = U.id_user INNER JOIN clients AS C ON C.id = T.id_client  WHERE $whereEvaluator U.user_status = 1 AND P.status = 1 AND P.description = 'Colaborador';");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id_user' => $row['id_user'],
                    'name' => $row['name'],
                    'user_registered' => $row['user_registered'],
                    'user_evaluation_date' => $row['user_evaluation_date'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Colaboradores Encontrados", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Colaboradores no encontrados", "payload" => []);
        }
        return $response;
    }
    public static function validEvaluator($id_collaborator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $query = "SELECT U.id AS id_user, C.description, C.id fROM user_relations AS U  
                        INNER JOIN clients AS C ON C.id = U.id_client
                        WHERE U.id_user = '$id_collaborator' AND C.status = 1;";
        $statement = $db->prepare($query);
        $statement->execute();
        $dates = [];
        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $dates = [
                    'id_user' => $row['id_user'],
                    'description' => $row['description'],
                    'id' => $row['id']
                ];
            }

        }else {
            $dates = FALSE;
        }
        return $dates;

        //return $statement->rowC ount();
    }


    public static function startEvaluation($id_collaborator, $id_evaluator)
    {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO evaluation_logs (question_id, user_id, evaluator_id)
              SELECT Q.id, " . $id_collaborator . ", " . $id_evaluator . "
              FROM questions AS Q
              WHERE Q.state_type = 1 ;";

        $statement = $db->prepare($query);
        $statement->execute();

        $insertedRows = $statement->rowCount();

        $updateUserDate = self::updateUserDate($id_collaborator);
        $updateEvaluationHistory = self::updateEvaluationHistory($id_collaborator, $id_evaluator);

        if ((($insertedRows > 0) &&  ($updateUserDate > 0) &&  ($updateEvaluationHistory > 0)) ) {

            $lastInsertId = $db->lastInsertId();
            $selectQuery = "SELECT *
                        FROM evaluation_logs
                        WHERE id >= " . $lastInsertId;

            $selectStatement = $db->prepare($selectQuery);
            $selectStatement->execute();
            $insertedData = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

            return array("code" => 1, "message" => "Evaluacion creada exitosamente ", "payload" => $insertedData);
        } else {
            return array("code" => 0, "message" => "Error al crear Evaluacion ", "payload" => []);
        }
    }
    public static function updateUserDate($id_collaborator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $querydate = "UPDATE `user` SET `user_evaluation_date` = CURRENT_TIMESTAMP  WHERE `user`.`id_user`  = '$id_collaborator';";
        $statement = $db->prepare($querydate);
        $statement->execute();

        return $statement->rowCount();
    }

    public static function updateEvaluationHistory($id_collaborator, $id_evaluator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $querydate = "INSERT INTO `evaluation_history` (`id_user`, `id_evaluator`, `date_evaluation`) VALUES ( '$id_collaborator', '$id_evaluator', CURRENT_TIMESTAMP);";
        $statement = $db->prepare($querydate);
        $statement->execute();

        return $statement->rowCount();
    }

    public static function updateQuestion($id_log,$question_id,$user_id,$evaluator_id,$evaluated_range,$feedback,$date)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_log) && !empty($question_id) && !empty($user_id) && !empty($evaluator_id) && !empty($evaluated_range) && !empty($feedback)  && !empty($date)){
            $query = "UPDATE `evaluation_logs` SET `evaluated_range` = '$evaluated_range', `feedback` = '$feedback', `date` = '$date', `evaluator_id` = '$evaluator_id' WHERE `evaluation_logs`.`id` = '$id_log' AND `evaluation_logs`.`question_id` = '$question_id'  AND `evaluation_logs`.`user_id` = '$user_id'";
            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Pregunta guardada.", "payload" => "");
            }else{
                return array("code" => 1, "message" => "No se detectaron cambios.", "payload" => "");
            }

        }else{
            return array("code" => 3, "message" => "Datos Erroneos", "payload" => "");
        }
    }

    public static function dataGraphics($id_collaborator,$date){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT L.id,C.description AS category,Q.category_id, L.evaluated_range,Q.title,Q.description AS question,Q.minimum,Q.maximum,L.date
                                    FROM evaluation_logs AS L
                                    INNER JOIN questions AS Q ON Q.id = L.question_id AND Q.state_type = 1
                                    INNER JOIN categories AS C ON C.id = Q.category_id
                                    WHERE L.user_id = '$id_collaborator'  AND YEAR(L.date) = '$date'");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'category_id' => $row['category_id'],
                    'category' => $row['category'],
                    'evaluated_range' => $row['evaluated_range'],
                    'title' => $row['title'],
                    'question' => $row['question'],
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum'],
                    'date' => $row['date']
                ];
            }
            $response = array("code" => 1, "message" => "Rangos encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Resumen de rangos no encontradas", "payload" => []);
        }
        return $response;
    }


}