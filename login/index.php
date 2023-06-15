<?php   

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');

    require_once('../db/connectionDB.php');
    require_once('./user.php');

    function getUserFromDB ($conx, $usr, $pwd){
        try{

            $sql = "Select * From user Where (user_login = ?) And (user_pass = ?)";
            $sentencia = $conx->prepare($sql);
            $sentencia->bindParam(1, $usr);
            $sentencia->bindParam(2, $pwd);
            $sentencia->execute();
            $fila = $sentencia->fetch(PDO::FETCH_ASSOC);  

            if ($sentencia->rowCount() >= 1){
                
                $user = new User();
                $user->setId($fila["id_user"]);
                $user->setUserLogin($fila["user_login"]);     
                $user->setUserPass($fila["user_pass"]);
                $user->setUserEmail($fila["user_email"]);             
                $user->setUserRegistered($fila["user_registered"]);  
                $user->setUserStatus($fila["user_status"]);
                $user->setFirstName($fila["first_name"]);     
                $user->setLastName($fila["last_name"]);
                $user->setUserProfile($fila["user_profile"]);             
                $user->setUserClient($fila["user_client"]); 

                return $user;

            }else{
                return false;                
            }
        }catch(Exception $e){
            echo "<br/> error user : ".$e->getMessage()."  <br/>"; 
            return null;               
        }
    }    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       
        // Guarda la conexión a la BD
        $conx = null;    

        // Filtramos por el método POST
        $body = file_get_contents('php://input');   
        $body = json_decode($body); 

        try {     
            
            // Se configura la conexión a la BD evaluacion
            //$connection = new ConnectionDB("localhost", "evaluacion", "root", "");
            // Realiza la conexión a BD-MySql
            //$conx = $connection->connect();

            if (!isset($body->user) || !isset($body->pass)){
                // codigo de estatus
                http_response_code(400); 
                // Cabecera que indica el tipo de contenido a servir
                header('Content-Type: application/json'); 
                $response = array("code" => 2, "message" => "Elementos del body son incorrectos, faltan o tienen valores erroneos", "payload" => "");   
                echo json_encode($response);
                return null;               
            }

            // Consulta si existe un usuario y retorna sus datos si lo encuentra, code (1), statusCode(200), de lo contrario, code (0), statusCode(400)
            //$userLoged = getUserFromDB($conx, $body->user, $body->pass);

            $response = null;
            $statusCode = 200;

/*             if($userLoged){       
                $statusCode = 200;
                $response = array("code" => 1, "message" => "Usuario encontrado", "payload" => $userLoged);
            }else{   
                $statusCode = 404;             
                $response = array("code" => 0, "message" => "Usuario no encontrado", "payload" => "");  
            } */  
            
            // configuración de la respuesta
            // Cabecera que indica el tipo de contenido a servir

            
            // codigo de estatus
            http_response_code($statusCode);
            echo json_encode($body);                     
            //echo json_encode($response);             

        } catch(Exception $e) {

            // Cabecera que indica el tipo de contenido a servir
            header('Content-Type: application/json');

            // codigo de estatus
            http_response_code(500);
                            
            $response = array("code" => -1, "message" => $e->getMessage(), "payload" => ""); 
            echo json_encode($response); 

        }

    }else{
        // Cabecera que indica el tipo de contenido a servir
        header('Content-Type: application/json'); 
        http_response_code(405);
        echo json_encode(array("code" => 405, "message" => "Método no permitido", "payload" => "")); 
    }
?>