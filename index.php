<?php


    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('content-type: application/json; charset=utf-8');

    require_once ("models/login.php");
    require_once ("models/user.php"); 
    require_once ("models/all.php");   
    require_once ("models/evaluation.php");      

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
            switch ($data["valid"]){
                case 'login':
                    // código de status
                    http_response_code(200);  
                    echo json_encode(login::loginIn($data['user'],$data['pass']));
                    break;
                case 'newUser':
                    $res = user::newUser($data['user_pass'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_registered'],$data['user_evaluation_date'],$data['user_job'],$data['id_client'],$data['id_evaluator']);
                    if ($res["code"] == 1){
                        $scode = 200;
                    }else{
                        $scode = 400;
                        if ($res["code"] === -1){
                           $scode = 500; 
                        }
                    }
                    http_response_code($scode );
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'allUser':
                    http_response_code(200);
                    echo json_encode(user::getAllUser());
                    break;
                case 'selectUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getUserJob());
                    break;                
                case 'selectUser':
                    $scode = 200;
                    $res = user::getUser($data['id_user']);
                    if ($res["code"] == 0 ){
                        $scode = 400;
                    }else{
                        if ($res["code"] == -1){
                           $scode = 500; 
                        }
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode(user::getUser($data['id_user']));
                    break;
                case 'selectCategories':

                    $res = all::getCategories();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'selectProfiles':
                    $scode = 200;
                    $res = all::getProfiles();
                    if ($res["code"] == -1 ){
                        $scode = 500;
                    }                   
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'selectClients':
                    $res = all::getClients();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'selectStates':
                    $res = all::getStates();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;                                        
                case 'newQuestion':
                    $res = (evaluation::newQuestion($data['category_id'],$data['state_type'],$data['title'],$data['description'],$data['minimum'],$data['maximum'],$data['type']));
                    if ($res["code"] == 1){
                        $scode = 200;
                    }else{
                        $scode = 400;
                        if ($res["code"] === -1){
                           $scode = 500; 
                        }
                    }
                    http_response_code($scode);                    
                    header('Content-Type: application/json');
                    //echo json_encode(evaluation::newQuestion($data['category_id'],$data['range_id'],$data['state_type'],$data['title'],$data['description']));
                    echo json_encode($res);
                    break;                
                case 'selectAllQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::selectAllQuestion());
                    break;               
                case 'userDateEvaluation':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::userDateEvaluation());
                    break;
                case 'userDateEvaluationMonth':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::userDateEvaluation($data['month'],$data['year']));
                    break;
                case 'userDateEvaluationDay':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::userDateEvaluation($data['month'],$data['year'],$data['day']));
                    break;
                case 'newProfiles':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newProfiles($data['description']));
                    break;                
                case 'newStates':
                    $res = json_encode(all::newStates($data['description']));
                    if ($res){
                        http_response_code(200);                        
                    }else{
                        http_response_code(200);                         
                    }
                    header('Content-Type: application/json');
                    echo $res;
                    break;
                case 'newClients':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newClients($data['description']));
                    break;                
                case 'newCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::newCategories($data['description']));
                    break;                
                case 'selectEvaluators':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::getEvaluators());
                    break;                
                case 'newUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::newUserJob($data['description']));
                    break;
                case 'getSelectCategories':
                    $res = all::getSelectCategories();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'getSelectProfiles':
                    $res = all::getSelectProfiles();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'getSelectClients':
                    $res = all::getSelectClients();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
                case 'getSelectUserJob':
                    $res = all::getSelectUserJob();
                    if ($res["code"] == -1){
                        $scode = 500;
                    }else{
                        $scode = 200;
                    }
                    http_response_code($scode);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;
/* Evaluador*/

                case 'getCollaboratorsEvaluator':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::getCollaboratorsEvaluator($data['evaluatorUser']));
                    break;
                case 'startEvaluation':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::startEvaluation($data['id_collaborator'], $data['id_evaluator']));
                    break;


                /*FIN  Evaluador*/

                    default:
                http_response_code(400);
            }
            break;
        case 'PUT':
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            switch ($data['valid']){
                case 'updateCategories':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateCategories($data['id']));
                    break;                
                case 'updateStates':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateStates($data['id']));
                    break;
                case 'updateUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updateUser($data['id_user'],$data['user_email'],$data['first_name'],$data['last_name'],$data['user_profile'],$data['user_evaluation_date'],$data['user_job'],$data['id_client'],$data['id_evaluator']));
                    break;
                case 'deleteUser':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::deleteUser($data['id_user']));
                    break;
                case 'deleteQuestion':
                    $res = evaluation::deleteQuestion($data['id']);
                    if ($res["code"] == 1){
                        $code = 200;
                    }else{
                        if ($res["code"] == 0){
                            $code = 400;
                        }else{
                            if ($res["code"] == 2){
                              $code = 404;  
                            }else{
                              $code = 500;   
                            }
                        }
                    }
                    http_response_code($code);
                    header('Content-Type: application/json');
                    echo json_encode($res);
                    break;                
                case 'updatePass':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updatePass($data['user_correo'],$data['user_pass']));
                    break; 
                case 'updateProfiles':
                    $res = json_encode(all::updateProfiles($data['id']));
                    if ($res["code"] == 1){
                        $code = 200;
                    }else{
                        if ($res["code"] == 0){
                            $code = 400;
                        }else{
                            $code = 404;                            
                        }
                    }
                    http_response_code( $code);
                    header('Content-Type: application/json');
                    echo $res;
                    break;
                case 'updateClients':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(all::updateClients($data['id']));
                    break;                                                       
                case 'updateUserJob':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(user::updateUserJob($data['id']));
                    break;

                /* Evaluador*/
                case 'updateQuestion':
                    http_response_code(200);
                    header('Content-Type: application/json');
                    echo json_encode(evaluation::updateQuestion($data['id_log'],$data['question_id'],$data['user_id'], $data['evaluator_id'], $data['evaluated_range'], $data['feedback'], $data['date']));
                    break;


                /*FIN  Evaluador*/
                default:
                http_response_code(400);
            }
            break;
}


