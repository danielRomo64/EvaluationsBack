<?php
//12
require_once "connection/Connection.php";
class all {
    public static function getCategories(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT C.id, C.status, C.description FROM categories AS C");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
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


    public static function newCategories($description) {
            $connection = new Connection();
            $db = $connection->connect();

            $query = "INSERT INTO `categories` ( `status`, `description`) VALUES ( '1', '$description')";

            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Categoria Creado", "payload" => "") ;
            }
            http_response_code(404);
            return array("code" => 0, "message" => "Categoria no Creado", "payload" => "");
        }

    public static function updateCategories($id, $description , $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `categories` SET `status` = '$status', `description` = '$description' WHERE `categories`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Categoria Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Categoria no actualizado", "payload" => "");
        }
    }


    // ------------------------------------------------------------------------------------------------------------------------------------------------------
    public static function getProfiles(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT P.id, P.status, P.description FROM profiles AS P");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
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


    public static function newProfiles($description) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO profiles(description,status) VALUES ('$description','1')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Perfiles Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Perfiles no Creado", "payload" => "");
    }

    public static function updateProfiles($id, $description , $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `profiles` SET `status` = '$status', `description` = '$description' WHERE `profiles`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Perfiles Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Perfiles no actualizado", "payload" => "");
        }
    }
    // ------------------------------------------------------------------------------------------------------------------------------------------------------

    public static function getClients(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT E.id, E.status, E.description FROM clients AS E");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'status' => $row['status'],
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
    public static function newClients($description) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO clients(description,status) VALUES ('$description','1')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Clientes Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Clientes no Creado", "payload" => "");
    }

    public static function updateClients($id, $description , $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `clients` SET `status` = '$status', `description` = '$description' WHERE `clients`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Cliente Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Cliente no actualizado", "payload" => "");
        }
    }
    // ------------------------------------------------------------------------------------------------------------------------------------------------------

    public static function getStates(){
        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        $query = $db->query("SELECT S.id, S.description FROM states AS S");

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id' => $row['id'],
                    'description' => $row['description']
                ];
            }
            $response = array("code" => 1, "message" => "Estados encontradas", "payload" => $dates);
        }else {
            http_response_code(404);
            $response = array("code" => 0, "message" => "Estados no encontradas", "payload" => "");
        }
        return $response;
    }

    public static function newStates($description) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO states(description) VALUES ('$description')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Estados Creado", "payload" => "") ;
        }
        http_response_code(404);
        return array("code" => 0, "message" => "Estados no Creado", "payload" => "");
    }


    public static function updateStates($id, $description)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `states` SET `description` = '$description' WHERE `states`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }


        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Estado Actualizado", "payload" => "") ;
        }else{
            http_response_code(404);
            return array("code" => 0, "message" => "Estado no actualizado", "payload" => "");
        }
    }

    // ------------------------------------------------------------------------------------------------------------------------------------------------------




}