<?php
//12
require_once "connection/Connection.php";
class user {
    public static function newUser($user_login, $user_pass, $user_email, $first_name, $last_name, $user_profile, $user_client) {
        $connection = new Connection();
        $db = $connection->connect();

        $valid = self::validUser($user_email);
        if ($valid){
            $query = "INSERT INTO user($user_login, user_pass, user_email, first_name, last_name, user_profile, user_client, user_status,user_registered) VALUES ('$user_email', '$user_pass', '$user_email', '$first_name', '$last_name', '$user_profile', '$user_client',1,CURTIME())";

            $statement = $db->prepare($query);
            $statement->execute();
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
            return true;
        }else{
            return false;
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
            $query = $db->query("SELECT U.id_user, CONCAT(U.first_name,' ',U.last_name) AS name ,U.user_email, U.user_login,  if(u.user_status != 1, 'Desactivo', 'Activo') as states ,P.description AS profile, C.description AS client , U.user_registered
                FROM user as U
                INNER JOIN profiles as P on U.user_profile = P.id
                INNER JOIN clients as C on U.user_client = C.id ".$where);

            if ($query->rowCount() > 0) {
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id_user' => $row['id_user'],
                        'name' => $row['name'],
                        'user_email' => $row['user_email'],
                        'user_login' => $row['user_login'],
                        'states' => $row['states'],
                        'profile' => $row['profile'],
                        'profile' => $row['profile'],
                        'client' => $row['client']
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


    public static function updateUser($id_user, $user_email, $first_name, $last_name, $user_status, $user_profile, $user_client)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id_user) && !empty($user_email) && !empty($first_name) && !empty($last_name) && !empty($user_status) && !empty($user_profile) && !empty($user_client) ){
            $query = "UPDATE `user` SET `user_email` = '$user_email', `user_status` = '$user_status', `first_name` = '$first_name', `last_name` = '$last_name', `user_profile` = '$user_profile', `user_client` = '$user_client' WHERE `user`.`id_user` = '$id_user'";
            $statement = $db->prepare($query);
            $statement->execute();

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


            // $rowCount = $statement->rowCount();
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




}








