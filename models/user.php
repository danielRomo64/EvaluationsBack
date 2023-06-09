<?php
//12
require_once "connection/Connection.php";
class user {
    public static function newUser($user_pass, $user_email, $first_name, $last_name, $user_profile, $user_registered,$user_evaluation_date,$user_job,$id_client,$id_evaluator) {
        $connection = new Connection();
        $db = $connection->connect();


        $valid = self::validUser($user_email);


        if ($valid){
            $query = "INSERT INTO `user` ( `user_login`, `user_pass`, `user_email`, `user_registered`, `user_status`, `first_name`, `last_name`, `user_profile`, `user_evaluation_date`, `user_job`) VALUES ('$user_email', '$user_pass', '$user_email','$user_registered', '1' ,'$first_name', '$last_name', '$user_profile', '$user_evaluation_date','$user_job')";

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


    public  static  function insertUserRelations($idUserNew,$id_client,$id_evaluator){
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO `user_relations` (`id_user`, `id_client`, `id_evaluator`) VALUES (  '$idUserNew','$id_client', '$id_evaluator')";
        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return false;
        }else{
            return true;
        }
    }


    public static function getAllUser($id_user = null ) {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        $dates = [];
        $response = [];
        $where = '';
        if (isset($id_user)){
            $where = "WHERE U.id_user = ".$id_user;
        }
        if ($db) {
            $query = $db->query("SELECT U.id_user, CONCAT(U.first_name,' ',U.last_name) AS name ,U.user_email, U.user_login,  if(u.user_status != 1, 'Desactivo', 'Activo') AS states ,P.description AS profile, C.description AS client , U.user_registered, if(P.description = 'Evaluador' || P.description = 'Administrador' ,'',U.user_evaluation_date) AS user_evaluation_date, J.description AS job_user, (SELECT CONCAT(X.first_name,' ',X.last_name) AS name_ev FROM user AS X WHERE X.id_user = R.id_evaluator ) AS name_evaluator
                                        FROM user as U
                                        INNER JOIN profiles as P on U.user_profile = P.id
                                        INNER JOIN user_relations AS R ON U.id_user = R.id_user
                                        INNER JOIN clients as C on R.id_client = C.id 
                                        INNER JOIN user_job AS J ON U.user_job = J.id".$where);

            if ($query->rowCount() > 0) {
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
            }else {
                http_response_code(404);
                echo json_encode( array("code" => 0, "message" => "Usuario no encontrado", "payload" => ""));
            }
        }
        return $response;

    }


    public static function updateUser($id_user, $user_email, $first_name, $last_name, $user_profile, $user_evaluation_date,$user_job,$id_client,$id_evaluator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_user) && !empty($user_email) && !empty($first_name) && !empty($last_name) && !empty($user_profile) && !empty($user_evaluation_date)  && !empty($user_job)  && !empty($id_client)  && !empty($id_evaluator) ){
            $query = "UPDATE `user` SET `user_login` = '$user_email', `user_email` = '$user_email', `first_name` = '$first_name', `last_name` = '$last_name', `user_profile` = '$user_profile', `user_evaluation_date` = '$user_evaluation_date', `user_job` = '$user_job' WHERE `user`.`id_user` = '$id_user'";
            $statement = $db->prepare($query);
            $statement->execute();

            $updateRelations = self::updateUserRelations($id_user,$id_client,$id_evaluator);
            if ($updateRelations){
                return array("code" => 0, "message" => "error con los datos de cliente usuario evaluador", "payload" => "");
            }


           // $rowCount = $statement->rowCount();
        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Usuario Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Usuario no actualizado", "payload" => "");
        }
    }


    public static function updateUserRelations($id_user,$id_client,$id_evaluator)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

            $query = "UPDATE `user_relations` SET `id_user` = '$id_user', `id_client` = '$id_client', `id_evaluator` = '$id_evaluator'  WHERE `user_relations`.`id_user` = '$id_user'";
            $statement = $db->prepare($query);
            $statement->execute();
            
        if ($statement->rowCount() > 0) {
            return false;
        }else{
            return true;
        }
    }

    public static function deleteUser($id_user, $user_status)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_user) && !empty($user_status)){
            $query = "UPDATE `user` SET `user_status` = '$user_status' WHERE `user`.`id_user` = '$id_user'";
            $statement = $db->prepare($query);
            $statement->execute();
        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Usuario Eliminado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Usuario no Eliminado", "payload" => "");
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
            }else {
                http_response_code(404);
                return array("code" => 0, "message" => "Contraseña Igual a la Anterior", "payload" => "");
            }


        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Contraseña Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Contraseña no Actualizado", "payload" => "");
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


//----------------------------------------------------
    public static function userDateEvaluation($month = null,$year = null, $day = null){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];
        $where = '';
        $whereYear = '';
        $whereDay = '';

        if (isset($month) && isset($year) && isset($day)) {
            $where = " MONTH(U.user_evaluation_date) = $month ";
            $whereYear = " AND YEAR(U.user_evaluation_date) = $year ";
            $whereDay = " AND DAY(U.user_evaluation_date) = $day ";

        } else if (isset($month) && isset($year)){
            $where = " MONTH(U.user_evaluation_date) = $month ";
            $whereYear = " AND YEAR(U.user_evaluation_date) = $year ";
        }else {
            $where = " MONTH(U.user_evaluation_date) = MONTH(NOW()) ";
            $whereYear = " AND YEAR(U.user_evaluation_date) = YEAR(NOW()) ";
        }



        $query = $db->query("SELECT U.id_user, C.description, CONCAT(U.first_name,' ',U.last_name) AS name, U.user_evaluation_date FROM user AS U INNER JOIN clients AS C ON U.user_client = C.id WHERE  $where $whereYear $whereDay");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id_user' => $row['id_user'],
                    'description' => $row['description'],
                    'name' => $row['name'],
                    'user_evaluation_date' => $row['user_evaluation_date']
                ];
            }
            $response = array("code" => 1, "message" => "Usuarios con Evaluaciones Pendientes", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "No se Encontró Ningún Usuario", "payload" => "");
        }
        return $response;
    }

//-----------------------------------------

    public static function getUserJob(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT U.id, U.status , U.description FROM user_job AS U");

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
            $response = array("code" => 0, "message" => "Cargos usuario no encontradas", "payload" => "");
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


    public static function updateUserJob($id, $description, $status)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `user_job` SET `description` = '$description', `status` = '$status' WHERE `user_job`.`id` = '$id'";
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






    public static function getEvaluators(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT U.id_user, CONCAT(C.description,' - ',U.last_name,' ', U.first_name) AS evaluator FROM user AS U INNER JOIN profiles  AS P ON P.id = U.user_profile INNER JOIN user_relations AS R ON R.id_user = U.id_user INNER JOIN clients AS C ON C.id = R.id_client WHERE P.description = 'Evaluador';");

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
            $response = array("code" => 0, "message" => "No se Encontro Evaluadores", "payload" => "");
        }
        return $response;
    }

}








