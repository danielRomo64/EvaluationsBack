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
                    echo json_encode(user::newUser($data['user_pass'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_registered'],$data['user_evaluation_date'],$data['user_job'],$data['id_client'],$data['id_evaluator']));
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
                    //--------------------------------------------------------------------------
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
                case 'selectStates':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::getStates());
                    break;
                case 'selectUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getUserJob());
                    break;
                //--------------------------------------------------------------------------
                case 'newQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::newQuestion($data['category_id'],$data['state_type'],$data['title'],$data['description'],$data['minimum'],$data['maximum'],$data['type']));
                    break;
                case 'questionCategory':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::questionCategory($data['category_id']));
                    break;
                case 'newCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::newCategories($data['description']));
                    break;
                case 'questionUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::questionUser($data['question_id'],$data['user_id'],$data['evaluator_id'],$data['evaluated_range'],$data['feedback']));
                    break;
                case 'userDateEvaluation':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::userDateEvaluation());
                    break;
                case 'userDateEvaluationMonth':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::userDateEvaluation($data['month'],$data['year']));
                    break;
                case 'userDateEvaluationDay':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::userDateEvaluation($data['month'],$data['year'],$data['day']));
                    break;
                //------------------------------------------------------------------------------------------------------------------------
                case 'newCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newCategories($data['description']));
                    break;
                //------------------------------------------------------------------------------------------------------------------------
                case 'newStates':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newStates($data['description']));
                    break;
                //------------------------------------------------------------------------------------------------------------------------
                case 'newProfiles':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newProfiles($data['description']));
                    break;
                //------------------------------------------------------------------------------------------------------------------------
                case 'newClients':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newClients($data['description']));
                    break;
                //------------------------------------------------------------------------------------------------------------------------
                case 'newUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::newUserJob($data['description']));
                    break;


                case 'selectEvaluators':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getEvaluators());
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
                    echo json_encode(user::updateUser($data['id_user'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_evaluation_date'],$data['user_job'],$data['id_client'],$data['id_evaluator']));
                    break;
                case 'deleteUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::deleteUser($data['id_user'],$data['user_status']));
                    break;
                case 'updateQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::updateEvaluation($data['id'],$data['category_id'],$data['state_type'],$data['title'],$data['description'],$data['minimum'],$data['maximum'],$data['type']));
                    break;
                case 'deleteQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::deleteQuestion($data['id'],$data['state_type']));
                    break;
                case 'updatePass':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updatePass($data['user'],$data['pass']));
                    break;
                //-------------------------------------------------------------------------------------------------------------------------------

                case 'updateStates':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateStates($data['id'],$data['description']));
                    break;
         //-------------------------------------------------------------------------------------------------------------------------------
                case 'updateCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateCategories($data['id'],$data['status']));
                    break;
                //-------------------------------------------------------------------------------------------------------------------------------
                case 'updateClients':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateClients($data['id'],$data['status']));
                    break;
                //-------------------------------------------------------------------------------------------------------------------------------
                case 'updateProfiles':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateProfiles($data['id'],$data['status']));
                    break;
                //-------------------------------------------------------------------------------------------------------------------------------
                case 'updateUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updateUserJob($data['id'],$data['status']));
                    break;
                default:
                    http_response_code(400);
            }
            break;
            http_response_code(405);
}



