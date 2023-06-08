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


        $existingQuery = "SELECT COUNT(*) FROM categories WHERE description = :description";
        $existingStatement = $db->prepare($existingQuery);
        $existingStatement->bindParam(':description', $description);
        $existingStatement->execute();
        $existingCount = $existingStatement->fetchColumn();

        if ($existingCount > 0) {
            return array("code" => 0, "message" => "Categoría ya existe", "payload" => "");
        }

        $insertQuery = "INSERT INTO categories(status, description) VALUES ('1', :description)";
        $insertStatement = $db->prepare($insertQuery);
        $insertStatement->bindParam(':description', $description);
        $insertStatement->execute();

        if ($insertStatement->rowCount() > 0) {
            return array("code" => 1, "message" => "Categoría Creada", "payload" => "") ;
        }

        http_response_code(404);
        return array("code" => 0, "message" => "Categoría no Creada", "payload" => "");
    }

    public static function updateCategories($id, $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `categories` SET `status` = '$status' WHERE `categories`.`id` = '$id'";
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

        $existingQuery = "SELECT COUNT(*) FROM profiles WHERE description = :description";
        $existingStatement = $db->prepare($existingQuery);
        $existingStatement->bindParam(':description', $description);
        $existingStatement->execute();
        $existingCount = $existingStatement->fetchColumn();

        if ($existingCount > 0) {
            return array("code" => 0, "message" => "Perfil ya existe", "payload" => "");
        }

        $insertQuery = "INSERT INTO profiles(description, status) VALUES (:description, '1')";
        $insertStatement = $db->prepare($insertQuery);
        $insertStatement->bindParam(':description', $description);
        $insertStatement->execute();

        if ($insertStatement->rowCount() > 0) {
            return array("code" => 1, "message" => "Perfil Creado", "payload" => "") ;
        }

        http_response_code(404);
        return array("code" => 0, "message" => "Perfil no Creado", "payload" => "");
    }

    public static function updateProfiles($id, $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `profiles` SET `status` = '$status' WHERE `profiles`.`id` = '$id'";
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

        $existingQuery = "SELECT COUNT(*) FROM clients WHERE description = :description";
        $existingStatement = $db->prepare($existingQuery);
        $existingStatement->bindParam(':description', $description);
        $existingStatement->execute();
        $existingCount = $existingStatement->fetchColumn();

        if ($existingCount > 0) {
            return array("code" => 0, "message" => "Cliente ya existe", "payload" => "");
        }

        $insertQuery = "INSERT INTO clients(description, status) VALUES (:description, '1')";
        $insertStatement = $db->prepare($insertQuery);
        $insertStatement->bindParam(':description', $description);
        $insertStatement->execute();

        if ($insertStatement->rowCount() > 0) {
            return array("code" => 1, "message" => "Cliente Creado", "payload" => "") ;
        }

        http_response_code(404);
        return array("code" => 0, "message" => "Cliente no Creado", "payload" => "");
    }

    public static function updateClients($id, $status)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id) && !empty($description)){
            $query = "UPDATE `clients` SET `status` = '$status' WHERE `clients`.`id` = '$id'";
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

        $existingQuery = "SELECT COUNT(*) FROM states WHERE description = :description";
        $existingStatement = $db->prepare($existingQuery);
        $existingStatement->bindParam(':description', $description);
        $existingStatement->execute();
        $existingCount = $existingStatement->fetchColumn();

        if ($existingCount > 0) {
            return array("code" => 0, "message" => "Estado ya existe", "payload" => "");
        }

        $insertQuery = "INSERT INTO states(description) VALUES (:description)";
        $insertStatement = $db->prepare($insertQuery);
        $insertStatement->bindParam(':description', $description);
        $insertStatement->execute();

        if ($insertStatement->rowCount() > 0) {
            return array("code" => 1, "message" => "Estado Creado", "payload" => "") ;
        }

        http_response_code(404);
        return array("code" => 0, "message" => "Estado no Creado", "payload" => "");
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