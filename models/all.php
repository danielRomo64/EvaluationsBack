<?php
//12
require_once "connection/Connection.php";
class all {
    
    public static function getCategories(){

        try{

            $dates = [];            
            
            $connection = new Connection();
            $db = $connection->connect();

            if ($db) {
                $query = $db->query("SELECT * FROM categories ");

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'status' => $row['status'] == 0 ? "Inactivo" : "Activado",                        
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "", "payload" => $dates);
                return $response;                   
            }else{
                $response = array("code" => -1, "message" => "problemas con la BD", "payload" => "");
                return $response;                 
            }

        }catch(exception $e){

            $response = array("code" => -1, "message" => "problemas al conectar a la BD", "payload" => $e->getMessage());
            return $response;

        }
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

    public static function updateCategories($id)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id)){
            $query = "UPDATE `categories` SET `status` = Not status WHERE `categories`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Categoria Actualizado", "payload" => "") ;
            }else{
                http_response_code(404);
                return array("code" => 0, "message" => "Categoria no actualizado", "payload" => "");
            }

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }
    }
    
    public static function getProfiles(){

        $connection = new Connection();
        $db = $connection->connect();
        $dates = [];

        if ($db ){
            $query = $db->query("SELECT * FROM profiles");

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'status' => $row['status'] == 0 ? "Inactivo" : "Activado",
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "Perfiles encontradas", "payload" => $dates); 
        }else{
            $response = array("code" => -1, "message" => "Problemas con la BD", "payload" => "");            
        }
        return $response ;
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
    
    public static function updateProfiles($id)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id)){
            $query = "UPDATE profiles SET status = NOT status  WHERE `profiles`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Perfiles Actualizado", "payload" => "") ;
            }else{
                return array("code" => 0, "message" => "Perfiles no actualizado", "payload" => "");
            }            
        }else {
            echo json_encode( array("code" => 2, "message" => "Datos Erroneos", "payload" => ""));
        }



    }

    public static function getClients(){

        try{

            $dates = [];            
            
            $connection = new Connection();
            $db = $connection->connect();

            if ($db) {
                $query = $db->query("SELECT * FROM clients ");

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'status' => $row['status'] == 0 ? "Inactivo" : "Activado",
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "", "payload" => $dates);
                return $response;                   
            }else{
                $response = array("code" => -1, "message" => "problemas con la BD", "payload" => "");
                return $response;                 
            }

        }catch(exception $e){

            $response = array("code" => -1, "message" => "problemas al conectar a la BD", "payload" => $e->getMessage());
            return $response;

        }        
    }

    public static function updateClients($id)    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id)){
            $query = "UPDATE `clients` SET `status` = Not status WHERE `clients`.`id` = '$id'";
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

    public static function getStates(){

        try{

            $dates = [];            
            
            $connection = new Connection();
            $db = $connection->connect();

            if ($db) {
                $query = $db->query("SELECT * FROM states");

                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'status' => $row['status'] == 0 ? "Inactivo" : "Activado",                        
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "", "payload" => $dates);
                return $response;                   
            }else{
                $response = array("code" => -1, "message" => "problemas con la BD", "payload" => "");
                return $response;                 
            }

        }catch(exception $e){

            $response = array("code" => -1, "message" => "problemas al conectar a la BD", "payload" => $e->getMessage());
            return $response;

        }        
    }

    public static function updateStates($id)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        if(!empty($id)){
            $query = "UPDATE `states` SET status = Not status WHERE `states`.`id` = '$id'";
            $statement = $db->prepare($query);
            $statement->execute();

            if ($statement->rowCount() > 0) {
                return array("code" => 1, "message" => "Estado Actualizado", "payload" => "") ;
            }else{
                http_response_code(400);
                return array("code" => 0, "message" => "Estado no actualizado", "payload" => "");
            }

        }else {
            http_response_code(404);
            echo json_encode( array("code" => 0, "message" => "Datos Erroneos", "payload" => ""));
        }

    }

    public static function newStates($description) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "INSERT INTO states(description) VALUES ('$description')";

        $statement = $db->prepare($query);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return array("code" => 1, "message" => "Estados Creado", "payload" => "") ;
        }else{
            return array("code" => 0, "message" => "Estados no Creado", "payload" => "");            
        }
    }

    public static function userDateEvaluation($month = null, $year = null, $day = null){
        
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

        $query = $db->query("SELECT U.id_user, C.description, CONCAT(U.first_name,' ',U.last_name) AS name, U.user_evaluation_date 
                            FROM user AS U 
                            INNER JOIN user_relations AS R ON R.id_user = U.id_user                             
                            INNER JOIN clients AS C ON C.id = R.id_client  
                            WHERE  $where $whereYear $whereDay");

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
            $response = array("code" => 0, "message" => "No se Encontró Ningún Usuario", "payload" => []);
        }
        return $response;
    }   

    public static function getSelectUserJob(){
        try {
            $dates = [];
            $connection = new Connection();
            $db = $connection->connect();
            if ($db) {
                $query = $db->query("SELECT id, description FROM user_job WHERE status = 1");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "Datos encontrados", "payload" => $dates);
                return $response;
            } else {
                $response = array("code" => -1, "message" => "Ningun dato activo o encontrado", "payload" => "");
                return $response;
            }
        } catch (exception $e) {
            $response = array("code" => -1, "message" => "problemas con mostrar la información", "payload" => $e->getMessage());
            return $response;
        }
    }  
    
    public static function getSelectProfiles(){
        try {
            $dates = [];
            $connection = new Connection();
            $db = $connection->connect();
            if ($db) {
                $query = $db->query("SELECT id, description FROM profiles WHERE status = 1");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "Datos encontrados", "payload" => $dates);
                return $response;
            } else {
                $response = array("code" => -1, "message" => "Ningun dato activo o encontrado", "payload" => "");
                return $response;
            }
        } catch (exception $e) {
            $response = array("code" => -1, "message" => "problemas con mostrar la información", "payload" => $e->getMessage());
            return $response;
        }
    }

    public static function getSelectClients(){
        try {
            $dates = [];
            $connection = new Connection();
            $db = $connection->connect();
            if ($db) {
                $query = $db->query("SELECT id, description FROM clients WHERE status = 1");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "Datos encontrados", "payload" => $dates);
                return $response;
            } else {
                $response = array("code" => -1, "message" => "Ningun dato activo o encontrado", "payload" => "");
                return $response;
            }
        } catch (exception $e) {
            $response = array("code" => -1, "message" => "problemas con mostrar la información", "payload" => $e->getMessage());
            return $response;
        }
    }  
    
    public static function getSelectCategories(){
        try {
            $dates = [];
            $connection = new Connection();
            $db = $connection->connect();
            if ($db) {
                $query = $db->query("SELECT id, description FROM categories WHERE status = 1");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    $dates[] = [
                        'id' => $row['id'],
                        'description' => $row['description']
                    ];
                }
                $response = array("code" => 1, "message" => "Datos encontrados", "payload" => $dates);
                return $response;
            } else {
                $response = array("code" => -1, "message" => "Ningun dato activo o encontrado", "payload" => "");
                return $response;
            }
        } catch (exception $e) {
            $response = array("code" => -1, "message" => "problemas con mostrar la información", "payload" => $e->getMessage());
            return $response;
        }
    }    
    
}