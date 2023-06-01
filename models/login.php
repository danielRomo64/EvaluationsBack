<?php


//hola
require_once "connection/Connection.php";
class login {
    public static function loginIn($user, $pass) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "SELECT U.id_user, U.first_name, U.last_name FROM user as U WHERE U.user_login = :user AND U.user_pass = :pass ";
        $statement = $db->prepare($query);
        $statement->bindParam(':user', $user);
        $statement->bindParam(':pass', $pass);

        $statement->execute();

        $state = self::getStatus($user);
        //echo $state;
        if ($state['user_status'] == 1) {
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $dates[] = [
                    'id_user' => $row['id_user'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name']
                ];
            }
        } else {
            $dates[] = ['user' => ['not found']];
        }
        return $dates;
    }

    public static function getStatus($user) {
        $connection = new Connection();
        $db = $connection->connect();

        $query = "SELECT * FROM user WHERE user_login=:user";
        $statement = $db->prepare($query);
        $statement->bindParam(':user', $user);
        $statement->execute();

        $dates = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $dates[] = [
                'user_status' => $row['user_status']
            ];
        }
        return $dates[0];
    }

}





