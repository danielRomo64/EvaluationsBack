<?php

require_once "connection/Connection.php";
class User {

    public static function newUser($user_pass, $user_email, $first_name, $last_name, $user_profile, $user_registered,$user_evaluation_date,$user_job,$id_client,$id_evaluator) {
        $connection = new Connection();
        $db = $connection->connect();

        $valid = self::validUser($user_email);

        if ($valid){
            $query = "INSERT INTO `user` ( `user_login`, `user_pass`, `user_email`, `user_registered`, `user_status`, `first_name`, `last_name`, `user_profile`, `user_evaluation_date`, `user_job`) 
            VALUES ('$user_email', '$user_pass', '$user_email','$user_registered', '1' ,'$first_name', '$last_name', '$user_profile', '$user_evaluation_date','$user_job')";

            $statement = $db->prepare($query);
            $statement->execute();
            $idUserNew = $db->lastInsertId();

            $validRelations = self::insertUserRelations($idUserNew,$id_client,$id_evaluator);
            if ($validRelations){
                return array("code" => 0, "message" => "error con los datos de cliente usuario evaluador", "payload" => "");
            }
        }else {
            http_response_code(404);
            return array("code" => 0, "message" => "Usuario ya Creado", "payload" => "");
        }

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Usuario Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Usuario no Creado", "payload" => "");
    }

    public static function getAllUser($id_user = null) {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        $dates = [];
        $response = [];

        if ($db) {
            $query = $db->query("SELECT U.id_user, CONCAT(U.first_name,' ',U.last_name) AS name ,U.user_email, U.user_login,  if(u.user_status != 1, 'inactivo', 'activo') AS states ,P.description AS profile, C.description AS client , U.user_registered, if(P.description = 'Evaluador' || P.description = 'Administrador' ,'',U.user_evaluation_date) AS user_evaluation_date, J.description AS job_user, (SELECT CONCAT(X.first_name,' ',X.last_name) AS name_ev FROM user AS X WHERE X.id_user = R.id_evaluator ) AS name_evaluator
                                        FROM user as U
                                        INNER JOIN profiles as P on U.user_profile = P.id
                                        INNER JOIN user_relations AS R ON U.id_user = R.id_user
                                        LEFT JOIN clients as C on R.id_client = C.id 
                                        LEFT JOIN user_job AS J ON U.user_job = J.id");

            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id_user' => $row['id_user'],
                    'name' => $row['name'],
                    'user_email' => $row['user_email'],
                    'user_login' => $row['user_login'],
                    'states' => $row['states'],
                    'profile' => $row['profile'],
                    'client' => $row['client'],
                    'user_registered' => $row['user_registered'],
                    'user_evaluation_date' => $row['user_evaluation_date'],
                    'job_user' => $row['job_user'],
                    'name_evaluator' => $row['name_evaluator']


                ];
            }
            $response = array("code" => 1, "message" => "Usuario encontrado", "payload" => $dates);
        }else{
            $response = array("code" => -1, "message" => "Problemas con la BD", "payload" => "");
        }

        return $response;
    }

    public static function getUser($id_user) {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        $dates = [];
        $response = [];

        if ($db) {
            $query = $db->query("SELECT U.id_user, U.first_name, U.last_name, U.user_email, U.user_login,  U.user_status ,U.user_profile, C.id AS client , U.user_registered, U.user_evaluation_date, U.user_job,
                                        R.id_evaluator
                                        FROM user as U
                                        LEFT JOIN user_relations AS R ON U.id_user = R.id_user
                                        LEFT JOIN clients as C on R.id_client = C.id  WHERE U.id_user = '$id_user'");

            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id_user' => $row['id_user'],
                        'first_name' => $row['first_name'],
                        'last_name' => $row['last_name'],
                        'user_email' => $row['user_email'],
                        'user_login' => $row['user_login'],
                        'user_status' => $row['user_status'],
                        'user_profile' => $row['user_profile'],
                        'client' => $row['client'],
                        'user_registered' => $row['user_registered'],
                        'user_evaluation_date' => $row['user_evaluation_date'],
                        'user_job' => $row['user_job'],
                        'id_evaluator' => $row['id_evaluator']
                    ];
                }
                $response = array("code" => 1, "message" => "Usuario encontrado", "payload" => $dates);
            }else {
                return array("code" => 0, "message" => "Usuario no encontrado", "payload" => "");
            }
        }else{
            $response = array("code" => 1, "message" => "Problemas de BD", "payload" => $dates);
        }
        return $response;

    }

    public static function updateUser($id_user, $user_email, $first_name, $last_name, $user_profile, $user_evaluation_date,$user_job,$id_client,$id_evaluator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_user) && !empty($user_email) && !empty($first_name) && !empty($last_name) && !empty($user_profile) && !empty($user_evaluation_date) ){
            $query = "UPDATE `user` SET `user_login` = '$user_email', `user_email` = '$user_email', `first_name` = '$first_name', `last_name` = '$last_name', `user_profile` = '$user_profile', `user_evaluation_date` = '$user_evaluation_date', `user_job` = '$user_job' WHERE `user`.`id_user` = '$id_user'";
            $statement = $db->prepare($query);
            $statement->execute();

            $updateRelations = self::updateUserRelations($id_user, $id_client, $id_evaluator);
            if (($statement->rowCount() > 0) || ($updateRelations > 0)){
                return array("code" => 1, "message" => "Actualización exitosa.", "payload" => "");                
            }else{
                return array("code" => 2, "message" => "No hubo cambios que actualizar en la BD", "payload" => "");
            }

        }else{
            return array("code" => 0, "message" => "Datos Erroneos", "payload" => "");
        }
    }  

    public static function updateUserRelations($id_user, $id_client, $id_evaluator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $query = "UPDATE `user_relations` SET `id_client` = '$id_client', `id_evaluator` = '$id_evaluator' 
        WHERE `user_relations`.`id_user` = '$id_user'";

        $statement = $db->prepare($query);
        $statement->execute();

        return $statement->rowCount();
    }    
    
    public static function deleteUser($id_user) {
        
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        if ($db){
            if(!empty($id_user)){

                $query = "UPDATE user 
                        SET user_status = Not user_status 
                        WHERE id_user = :id";

                $statement = $db->prepare($query);
                $statement->bindParam(':id', $id_user);
                $statement->execute();
                if ($statement->rowCount() > 0){
                    return array("code" => 1, "message" => "El estado del usuario ha sido cambiado", "payload" => "");                    
                }else{
                    return array("code" => 2, "message" => "Usuario no encontrado", "payload" => ""); 
                }
            }else {
                return array("code" => 0, "message" => "Faltan datos para realizar la operación", "payload" => "");
            }            
        }else{
            return json_encode( array("code" => -1, "message" => "Problemas de conexión a BD", "payload" => ""));
        }
    }   
    
    public  static  function validUser($user_email){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "SELECT * FROM user AS U  WHERE U.user_login = '$user_email' ";
        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return false;
        }else{
            return true;
        }
    }    

    public  static  function validUserUpdate($user_email, $id){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "SELECT U.user_email FROM user AS U  WHERE (U.user_login = '$user_email') And (U.id_user <> '$id')";
        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }else{
            return false;
        }
    }    

    public static function updatePass($user, $pass)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($user) && !empty($pass)){

            $valid = self::validUserPass($user, $pass);
            if ($valid){
                $query = "UPDATE `user` SET `user_pass` = '$pass' WHERE `user`.`user_login` = '$user'";
                $statement = $db->prepare($query);
                $statement->execute();

                if ($statement->rowCount() > 0) {
                    return array("code" => 1, "message" => "Contraseña ha sido actualizada", "payload" => "") ;
                }else{
                    return array("code" => 0, "message" => "Contraseña no pudo ser actualizada", "payload" => "");
                }

            }else {
                http_response_code(404);
                return array("code" => 2, "message" => "La contraseña Igual a la anterior", "payload" => "");
            }
        } 
    }
    
    public  static  function validUserPass($user, $pass){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "SELECT * FROM user AS U  WHERE U.user_login = '$user' AND U.user_pass = '$pass' ";
        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return false;
        }else{
            return true;
        }
    }    

    public static function getUserJob(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT U.id, if(u.status != 1, 'inactivo', 'activo') AS status, U.description FROM user_job AS U");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Cargos usuario encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Cargos usuario no encontradas", "payload" => []);
        }
        return $response;
    }   
    
    public  static  function insertUserRelations($idUserNew,$id_client,$id_evaluator){
        $connection = new Connection();
        $db = $connection->connect();

        if ($id_evaluator == ""){
            $id_evaluator = 0;
        }
        $query = "INSERT INTO `user_relations` (`id_user`, `id_client`, `id_evaluator`) VALUES (  '$idUserNew','$id_client', ".$id_evaluator.")";
        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return false;
        }else{
            return true;
        }
    }

    public static function getEvaluators(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT U.id_user, CONCAT(C.description,' - ',U.last_name,' ', U.first_name) AS evaluator 
        FROM user AS U 
        INNER JOIN profiles  AS P ON P.id = U.user_profile 
        INNER JOIN user_relations AS R ON R.id_user = U.id_user 
        INNER JOIN clients AS C ON C.id = R.id_client 
        WHERE P.description = 'Evaluador';");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id_user' => $row['id_user'],
                    'evaluator' => $row['evaluator']
                ];
            }
            $response = array("code" => 1, "message" => "Evaluadores", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "No se Encontro Evaluadores", "payload" => []);
        }
        return $response;
    }

    public static function newUserJob($description) {
        $connection = new Connection();
        $db = $connection->connect();


        $existingQuery = "SELECT COUNT(*) FROM user_job WHERE description = :description";
        $existingStatement = $db->prepare($existingQuery);
        $existingStatement->bindParam(':description', $description);
        $existingStatement->execute();
        $existingCount = $existingStatement->fetchColumn();

        if ($existingCount > 0) {
            return array("code" => 0, "message" => "Cargo ya existe", "payload" => "");
        }


        $insertQuery = "INSERT INTO user_job(description, status) VALUES (:description, '1')";
        $insertStatement = $db->prepare($insertQuery);
        $insertStatement->bindParam(':description', $description);
        $insertStatement->execute();

        if ($insertStatement->rowCount() > 0) {
            return array("code" => 1, "message" => "Cargo Creado", "payload" => "") ;
        }

        http_response_code(404);
        return array("code" => 0, "message" => "Cargo no Creado", "payload" => "");
    }

    public static function updateUserJob($id)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id)){
            $query = "UPDATE `user_job` 
                    SET status = Not status 
                    WHERE `user_job`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Cargo Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Cargo no actualizado", "payload" => "");
        }
    }    
}
/*  */