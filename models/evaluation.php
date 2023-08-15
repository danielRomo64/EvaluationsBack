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
        if ((strpos(strtolower($validEvaluator['description']), 'eliza') !== false)||(strpos(strtolower($validEvaluator['description']), 'admin') !== false)){
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

        $query = "SELECT U.id_user AS id_user, IF(U.id_client = 0, 'admin', C.description) AS description, IF(U.id_client = 0, 0,C.id) AS id
                    FROM user_relations AS U  
                    LEFT JOIN (
                      SELECT id, status, description
                      FROM clients
                      WHERE status = 1
                    ) AS C ON C.id = U.id_client
                    WHERE U.id_user = '$id_collaborator';";
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
    public static function startEvaluationValid($id_collaborator, $date)
    {
        $connection = new Connection();
        $db = $connection->connect();

        $activeQuery = "SELECT id , status FROM evaluation_history WHERE id_user = :id AND date_evaluation = :date ORDER BY id DESC LIMIT 1;";
        $statement = $db->prepare($activeQuery);
        $statement->bindParam(':id', $id_collaborator, PDO::PARAM_INT);
        $statement->bindParam(':date', $date, PDO::PARAM_STR);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $dates = $row ? ['status' => $row['status']] : false;

        return $dates;
    }
    public static function startEvaluationNew($id_collaborator, $id_evaluator, $date)
    {
        $connection = new Connection();
        $db = $connection->connect();
        $startEvaluationValid = self::startEvaluationValid($id_collaborator, $date);

        if ($startEvaluationValid['status'] == 0) {
            return array("code" => 0, "message" => "Ya existe una evaluación creada edítele o cierre la antes de continuar con una nueva ", "payload" => []);
        } else if($startEvaluationValid['status'] == 1) {
             try {

                $query = "INSERT INTO evaluation_logs (question_id, user_id, evaluator_id, date)
                        SELECT Q.id, " . $id_collaborator . ", " . $id_evaluator . ", '" . $date . "'
                        FROM questions AS Q
                        WHERE Q.state_type = 1 ;";

                $statement = $db->prepare($query);
                $statement->execute();

                $insertedRows = $statement->rowCount();

                $updateUserDate = self::updateUserDate($id_collaborator, $date);
                $updateEvaluationHistory = self::updateEvaluationHistory($id_collaborator, $id_evaluator, $date);

                $lastInsertId = $db->lastInsertId();
                $selectQuery = "SELECT E.id,E.question_id, E.user_id,E.evaluator_id,E.evaluated_range,E.feedback,E.date,
                                        C.id AS id_categorie, C.description AS categorie ,Q.title,Q.description,Q.minimum,Q.maximum
                                        FROM evaluation_logs AS E  
                                        INNER JOIN questions AS Q ON E.question_id = Q.id
                                        INNER JOIN categories AS C ON C.id = Q.category_id
                                        WHERE Q.state_type = 1 AND C.status = 1 AND E.id >= " . $lastInsertId;

                $selectStatement = $db->prepare($selectQuery);
                $selectStatement->execute();
                $insertedData = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

                return array("code" => 2, "message" => "Evaluacion creada exitosamente ", "payload" => $insertedData);
            } catch (Exception $e) {
                return array("code" => 1, "message" => "Se presento error. Por favor contacte al administrado.", "payload" => []);
            }
        }
    }
    public static function startEvaluation($id_collaborator, $id_evaluator, $date)
    {
        $connection = new Connection();
        $db = $connection->connect();

        $startEvaluationValid = self::startEvaluationValid($id_collaborator,$date);

        if ($startEvaluationValid['status'] == 0){

            $selectQuery = "SELECT E.id,E.question_id, E.user_id,E.evaluator_id,E.evaluated_range,E.feedback,E.date,
            C.id AS id_categorie, C.description AS categorie ,Q.title,Q.description,Q.minimum,Q.maximum
            FROM evaluation_logs AS E  
            INNER JOIN questions AS Q ON E.question_id = Q.id
            INNER JOIN categories AS C ON C.id = Q.category_id
            WHERE Q.state_type = 1 AND C.status = 1 AND E.user_id = $id_collaborator AND E.date = '$date'";

            $selectStatement = $db->prepare($selectQuery);
            $selectStatement->execute();
            $insertedData = $selectStatement->fetchAll(PDO::FETCH_ASSOC);

                return array("code" => 1, "message" => "Evaluacion ya creada", "payload" => $insertedData);
        }else{
            return array("code" => 0, "message" => "Evaluacion ya finalizada", "payload" => []);
        }
    }
    public static function validUserEvaluation($id_collaborator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $query = "SELECT E.id, E.id_evaluator,E.date_evaluation FROM evaluation_history AS E WHERE E.id_user = '$id_collaborator' AND YEAR(E.date_evaluation) = YEAR(CURRENT_DATE) AND MONTH(E.date_evaluation) = MONTH(CURRENT_DATE) ;";
        $statement = $db->prepare($query);
        $statement->execute();
        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $dates = [
                    'id' => $row['id'],
                    'id_evaluator' => $row['id_evaluator'],
                    'date_evaluation' => $row['date_evaluation']
                ];
            }

        }else {
            $dates = FALSE;
        }
        return $dates;
    }
    public static function updateUserDate($id_collaborator,$date)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $querydate = "UPDATE `user` SET `user_evaluation_date` = '$date'  WHERE `user`.`id_user`  = '$id_collaborator';";
        $statement = $db->prepare($querydate);
        $statement->execute();

        return $statement->rowCount();
    }
    public static function updateEvaluationHistory($id_collaborator, $id_evaluator, $date)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $querydate = "INSERT INTO `evaluation_history` (`id_user`, `id_evaluator`, `date_evaluation`, `status`) VALUES ( '$id_collaborator', '$id_evaluator', '$date','0');";
        $statement = $db->prepare($querydate);
        $statement->execute();

        return $statement->rowCount();
    }
    public static function updateQuestion($id_log,$user_id,$evaluator_id,$evaluated_range,$feedback)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_log)  && !empty($user_id) && !empty($evaluator_id)){
            $query = "UPDATE `evaluation_logs` SET `evaluated_range` = '$evaluated_range', `feedback` = '$feedback' , `evaluator_id` = '$evaluator_id' WHERE `evaluation_logs`.`id` = '$id_log' AND `evaluation_logs`.`user_id` = '$user_id'";
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
    public static function dataGraphics($id_collaborator, $date){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT L.id,C.description AS category,Q.category_id, L.evaluated_range,Q.title,Q.description AS question,Q.minimum,Q.maximum,L.date, feedback, 
                            concat(U.first_name, ' ', U.last_name) as name,
                            (SELECT J.description FROM user_relations as R INNER JOIN user_job as J ON J.id = R.id_client WHERE R.id_user ='$id_collaborator') AS job,
                            (SELECT C.description FROM user_relations as R INNER JOIN clients  AS  C ON C.id = R.id_client WHERE R.id_user ='$id_collaborator') AS client,
                            
                            (SELECT CONCAT(E.first_name,' ',E.last_name) FROM user_relations as R INNER JOIN user  AS  E ON E.id_user = R.id_evaluator WHERE R.id_user ='34') AS evaluator
                            FROM evaluation_logs AS L
                            INNER JOIN questions AS Q ON Q.id = L.question_id AND Q.state_type = 1
                            INNER JOIN categories AS C ON C.id = Q.category_id
                            INNER JOIN user AS U ON U.id_user = '$id_collaborator'                         
                            WHERE L.user_id = '$id_collaborator'  AND YEAR(L.date) = '$date'");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],                    
                    'category_id' => $row['category_id'],
                    'category' => $row['category'],
                    'evaluated_range' => $row['evaluated_range'],
                    'title' => $row['title'],
                    'question' => $row['question'],
                    'feedback' => $row['feedback'],                    
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum'],
                    'date' => $row['date'],
                    'job' => $row['job'],
                    'client' => $row['client'],
                    'evaluator' => $row['evaluator']

                ];
            }
            $response = array("code" => 1, "message" => "Rangos encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Resumen de rangos no encontradas", "payload" => []);
        }
        return $response;
    }
    public static function getEvaluation($id_collaborator)
    {
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT E.id,E.question_id, E.user_id,E.evaluator_id,E.evaluated_range,E.feedback,E.date,
                                C.id AS id_categorie, C.description AS categorie ,Q.title,Q.description,Q.minimum,Q.maximum
                                FROM evaluation_logs AS E  
                                INNER JOIN questions AS Q ON E.question_id = Q.id
                                INNER JOIN categories AS C ON C.id = Q.category_id
                                WHERE Q.state_type = 1 AND C.status = 1 AND E.id = '$id_collaborator'");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'question_id' => $row['question_id'],
                    'user_id' => $row['user_id'],
                    'evaluator_id' => $row['evaluator_id'],
                    'evaluated_range' => $row['evaluated_range'],
                    'date' => $row['date'],
                    'id_categorie' => $row['id_categorie'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum']
                ];
            }
            $response = array("code" => 1, "message" => "Pregunta encontrada", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Pregunta no encontrada", "payload" => []);
        }
        return $response;
    }
    public static function getNewEvaluation($id_collaborator, $id_evaluator, $validUserEvaluation = null){
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if ($validUserEvaluation){
            $where = " AND E.feedback >= 0   AND (YEAR(E.date) = YEAR('$validUserEvaluation') OR E.date = '0000-00-00');";
        }else{
            $where = " AND E.evaluator_id = $id_evaluator  AND E.feedback = 0  AND E.date = '0000-00-00' ";
        }

        $query = "SELECT E.id, E.question_id, E.user_id, E.evaluator_id, E.evaluated_range, E.feedback, E.date, C.id AS id_categorie, C.description AS categorie, Q.title, Q.description, Q.minimum, Q.maximum FROM evaluation_logs AS E INNER JOIN questions AS Q ON E.question_id = Q.id INNER JOIN categories AS C ON C.id = Q.category_id WHERE Q.state_type = 1 AND C.status = 1 AND E.user_id = $id_collaborator  $where";
        $statement = $db->prepare($query);
        $statement->execute();
        $response = [];

        if ($statement->rowCount() > 0) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $dates = [
                    'id' => $row['id'],
                    'question_id' => $row['question_id'],
                    'user_id' => $row['user_id'],
                    'evaluator_id' => $row['evaluator_id'],
                    'evaluated_range' => $row['evaluated_range'],
                    'feedback' => $row['feedback'],
                    'date' => $row['date'],
                    'id_categorie' => $row['id_categorie'],
                    'categorie' => $row['categorie'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum']
                ];

                $response[] = $dates;
            }
        }

        return $response;
        //return $query;
    }
    public static function getAllEvaluation($id_collaborator){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT E.user_id, C.description, E.date FROM evaluation_logs AS E INNER JOIN user_relations AS J ON J.id_user = E.user_id INNER JOIN clients AS C ON J.id_client = C.id WHERE E.user_id = $id_collaborator GROUP BY E.user_id, C.description, E.date;");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'user_id' => $row['user_id'],
                    'date' => $row['date'],
                    'description' => $row['description']

                ];
            }
            $response = array("code" => 1, "message" => "Todas las Evaluaciones encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Evaluaciones no encontradas", "payload" => []);
        }
        return $response;
    }
    public static function updateDateEvaluation($user_id,$evaluator_id,$date)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($user_id) && !empty($evaluator_id) && !empty($date)){
            $query = "UPDATE `evaluation_history` SET `status` = '1' WHERE   `evaluation_history`.`id_user` = '$user_id' AND `evaluation_history`.`id_evaluator` = '$evaluator_id' AND `evaluation_history`.`date_evaluation` = '$date' ";
            $statement = $db->prepare($query);
            $statement->execute();

            $newDate = date('Y-m-d', strtotime($date . ' +1 year'));
            $updateUserDate = self::updateUserDate($user_id,$newDate);
            if ($statement->rowCount() > 0 ) {
                if($updateUserDate > 0){
                    return array("code" => 1, "message" => "Actualizacion fecha usuario y evaluacion cerrada", "payload" => "") ;
                  }else{
                    return array("code" => 2, "message" => "Evaluacion cerrada, Fecha de usuario no actualizada", "payload" => "") ;
                }
            }else{
                http_response_code(404);
                return array("code" => 3, "message" => "Evaluacion no Modificada o datos no encontrados", "payload" => "");
            }

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos o incompletos", "payload" => ""));
        }


    }
    public static function validStartEvaluation($id_collaborator, $date , $id_evaluator)
    {
        if(!empty($id_collaborator) && !empty($date)) {
            $startEvaluationValid = self::startEvaluationValid($id_collaborator, $date);
            if ($startEvaluationValid) {
                if ($startEvaluationValid['status'] == 0) {
                    return array("code" => 1, "message" => "Evaluacion Abierta", "payload" => []);
                } else {
                    return array("code" => 2, "message" => "Evaluacion Finalizada", "payload" => []);
                }
            } else {
                return array("code" => 0, "message" => "Evaluacion no encontrada", "payload" => []);
            }
        }else{
            return array("code" => 0, "message" => "Datos Erroneos" , "payload" => []);
        }
    }



}