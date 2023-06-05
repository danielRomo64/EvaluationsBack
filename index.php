<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('content-type: application/json; charset=utf-8');

    require_once "models/login.php";
    require_once "models/user.php";
    require_once "models/all.php";
    require_once "models/evaluation.php";


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
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::newUser($data['user_login'],$data['user_pass'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_client']));
                    break;
                case 'allUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getAllUser());
                    break;
                case 'selectUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getAllUser($data['id_user']));
                    break;
                case 'selectCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::getCategories());
                    break;
                case 'selectProfiles':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::getProfiles());
                    break;
                case 'selectClients':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::getClients());
                    break;
                case ' >':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::getRanges());
                    break;
                case 'newRanges':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newRanges($data['minimum'],$data['maximum']));
                    break;
                case 'newQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::newQuestion($data['category_id'],$data['range_id'],$data['state_type'],$data['title'],$data['description']));
                    break;

                default:
                    http_response_code(400);
            }
            //http_response_code(405);
            break;

        case 'PUT':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);


            switch ($data['valid']){

                case 'updateUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updateUser($data['id_user'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_status'],$data['user_profile'],$data['user_client']));
                    break;
                case 'deleteUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::deleteUser($data['id_user'],$data['user_status']));
                    break;
                case 'updateRanges':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateRanges($data['id'],$data['minimum'],$data['maximum']));
                    break;
                case 'updateQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::updateEvaluation($data['category_id'],$data['range_id'],$data['state_type'],$data['title'],$data['description']));
                    break;
                default:
                    http_response_code(400);


            }
            break;
            http_response_code(405);

}



