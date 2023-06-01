<?php
//12
require_once "connection/Connection.php";
class User {
    public static function newUser($user_login, $user_pass, $user_email, $first_name, $last_name, $user_profile, $user_client) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO user(user_login, user_pass, user_email, first_name, last_name, user_profile, user_client, user_status,user_registered) VALUES ('$user_login', '$user_pass', '$user_email', '$first_name', '$last_name', '$user_profile', '$user_client',1,CURTIME())";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Usuario Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Usuario no Creado", "payload" => "");
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

            $rowCount = $statement->rowCount();
        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Usuario no actualizado", "payload" => ""));
        }


        if ($rowCount > 0) {
            return TRUE;
        }

        return FALSE;
    }

}








