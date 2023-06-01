<?php
    require_once "models/login.php";
    require_once "models/user.php";


    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            switch ($data['valid']){

                default:
                    http_response_code(405);

            }
            http_response_code(405);
            break;

        case 'POST':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            switch ($data['valid']){

                case 'login':
                    echo json_encode(login::loginIn($data['user'],$data['pass']));
                    http_response_code(200);
                    break;
                case 'newUser':
                    echo json_encode(User::newUser($data['user_login'],$data['user_pass'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_client']));
                    http_response_code(200);
                    break;
                case 'allUser':
                    echo json_encode(User::getAllUser());
                    http_response_code(200);
                    break;
                case 'selectUser':
                    echo json_encode(User::getAllUser($data['id_user']));
                    http_response_code(200);
                    break;
                default:
                    http_response_code(400);
            }
            http_response_code(405);
            break;

        case 'PUT':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);


            switch ($data['valid']){

                case 'updateUser':
                     echo json_encode(User::updateUser($data['id_user'],$data['pruebaac']));
                     http_response_code(200);
                     break;

                case 'deleteUser':
                    //echo json_encode(User::updateUser($data['id_user'],$data['pruebaac']));
                    http_response_code(200);
                    break;
                default:
                    http_response_code(400);


            }
            break;
            http_response_code(405);

}
//hik

