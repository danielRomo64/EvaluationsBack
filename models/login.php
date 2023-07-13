<?php


require_once "connection/Connection.php";
require 'vendor/autoload.php';

class login {
    public static function loginIn($user, $pass) {
        $connection = new Connection();
        $db = $connection->connect();
        if(!empty($user) && !empty($pass)){
            $query = "SELECT U.id_user, U.first_name, U.last_name, P.description as profile
            FROM user as U 
            INNER JOIN profiles AS P ON P.id = U.user_profile 
            WHERE U.user_login = :user  AND U.user_pass = :pass AND U.user_status = 1 AND P.status = 1";
            $statement = $db->prepare($query);
            $statement->bindParam(':user', $user);
            $statement->bindParam(':pass', $pass);

        }else{
            return array("code" => 0, "message" => "Usuario no encontrado", "payload" => "");
        }

        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if($statement->rowCount() > 0) {
            $fila = array(
                'id_user' => $row['id_user'],
                'first_name' => $row['first_name'],
                'last_name' => $row['last_name'],
                'profile' => $row['profile']
            );

            $tokenPayload = array(
                'user' => $user,
                'profile' => $row['profile']
            );

            $jwt = self::generateJWT($tokenPayload);

            if ($row['profile'] == "Administrador"){
                return array("code" => 1, "message" => "Usuario Administrador encontrado", "payload" => array("user" => $fila, "token" => $jwt));
            } else if ($row['profile'] == "Evaluador"){
                return array("code" => 2, "message" => "Usuario Evaluador encontrado", "payload" => array("user" => $fila, "token" => $jwt));
            } else {
                return array("code" => 0, "message" => "Usuario no encontrado", "payload" => "");
            }

        } else {
            return array("code" => 0, "message" => "Usuario no encontrado", "payload" => "");
        }
    }
    private static function generateJWT($payload) {
        $jwtSecretKey = 'your_secret_key';
        $jwtAlgorithm = 'HS256';
        $jwt = \Firebase\JWT\JWT::encode($payload, $jwtSecretKey, $jwtAlgorithm);
        return $jwt;
    }
}