<?php
//12
require_once "connection/Connection.php";
class all {
    public static function getCategories(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT C.id, C.description FROM categories AS C");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Categorias encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categorias no encontradas", "payload" => "");
        }
        return $response;

    }
    public static function getProfiles(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT P.id, P.description FROM profiles AS P");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Perfiles encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Perfiles no encontradas", "payload" => "");
        }
        return $response;

    }
    public static function getClients(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT E.id, E.description FROM clients AS E");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Categorias encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Categorias no encontradas", "payload" => "");
        }
        return $response;
    }

    public static function getRanges(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT R.id, R.minimum, R.maximum FROM ranges AS R");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'minimum' => $row['minimum'],
                    'maximum' => $row['maximum']
                ];
            }
            $response = array("code" => 1, "message" => "Rangos encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Rangos no encontradas", "payload" => "");
        }
        return $response;
    }

    public static function newRanges($minimum, $maximum) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO ranges(minimum, maximum) VALUES ('$minimum', '$maximum')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Rangos Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Rangos no Creado", "payload" => "");
    }


    public static function updateRanges($id, $minimum, $maximum)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($minimum) && !empty($maximum) ){
            $query = "UPDATE `ranges` SET `minimum` = '$minimum', `maximum` = '$maximum' WHERE `ranges`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

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




}