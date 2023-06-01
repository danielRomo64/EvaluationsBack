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
            return true;
        }
        return false;
    }



    public static function getAllUser($id_user = null ) {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();
        $dates = [];
        $where = '';
        if (isset($id_user)){
            $where = "WHERE U.id_user = ".$id_user;
        }
        if ($db) {
            $query = $db->query("SELECT U.id_user, CONCAT(U.first_name,' ',U.last_name) AS name ,U.user_email, U.user_login,  if(u.user_status != 1, 'Desactivo', 'Activo') as states ,P.description AS profile, C.description AS client , U.user_registered
                FROM user as U
                INNER JOIN profiles as P on U.user_profile = P.id
                INNER JOIN clients as C on U.user_client = C.id ".$where);
            //print_r($query);
            //exit();
            if ($query) {
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
            }
        }
        return $dates;

    }


    public static function updateUser($id_user, $pruebaac)
    {
        $dbConnection = new Connection();
        $db = $dbConnection->connect();

        $query = "UPDATE `user` SET user_login = '$pruebaac' WHERE `user`.id_user = $id_user";
        $statement = $db->prepare($query);
        $statement->execute();

        $rowCount = $statement->rowCount();

        if ($rowCount > 0) {
            return TRUE;
        }

        return FALSE;
    }

}




/*
public static function updateUser($id_user, $pruebaac) {
    $dbConnection = new PDO("mysql:host=localhost;dbname=nombre_base_de_datos", "usuario", "contraseña");
    // Reemplaza "nombre_base_de_datos", "usuario" y "contraseña" con los valores adecuados para tu base de datos

    $query = "UPDATE `user` SET user_login = :pruebaac WHERE id_user = :id_user";
    $statement = $dbConnection->prepare($query);
    $statement->bindParam(':pruebaac', $pruebaac);
    $statement->bindParam(':id_user', $id_user);
    $statement->execute();

    $rowCount = $statement->rowCount();

    if($rowCount > 0) {
        return TRUE;
    }

    return FALSE;
}


*/



