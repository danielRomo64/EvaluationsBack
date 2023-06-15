<?php   
    class User {

        public  $id_user = 0;
        public  $user_login = "";
        public  $user_pass = ""; 
        public  $user_email = "";  
        public  $user_registered = "" ; 
        public  $user_status = "";
        public  $first_name = ""; 
        public  $last_name = "";  
        public  $user_profile = "" ; 
        public  $user_client = "" ;         

        function __construct(){ }
        
        function init($id_user,$user_login,$user_pass,$user_email,$user_registered,$user_status,$first_name,$last_name,$user_profile,$user_client){
        
            $this->id_user =            $id_user;
            $this->user_login =         $user_login;
            $this->user_pass =          $user_pass; 
            $this->user_email =         $user_email;  
            $this->user_registered =    $user_registered; 
            $this->user_status =        $user_status;
            $this->first_name =         $first_name; 
            $this->last_name =          $last_name;  
            $this->user_profile=        $user_profile; 
            $this->user_client =        $user_client;  
        }

        function setId($id){
            $this->id_user = $id;
        }
        function getId(){
           return $this->id_user;
        }

        function setUserLogin($usr){
            $this->user_login = $usr;
        }
        function getUserLogin(){
           return $this->id_user;
        }

        function setUserPass($pwd){
            $this->user_pass = $pwd;
        }
        function getUserPass(){
           return $this->user_pass;
        }

        function setUserEmail($email){
            $this->user_email = $email;
        }
        function getUserEmail(){
           return $this->user_email;
        }  
        
        function setUserRegistered($registered){
            $this->user_registered = $registered;
        }
        function getUserRegistered(){
           return $this->user_registered;
        }     

        function setUserStatus($user_status){
            $this->user_status = $user_status;
        }
        function getUserStatus(){
           return $this->user_status;
        }            
        
        function setFirstName($firstName){
            $this->first_name = $firstName;
        }
        function getFirstName(){
           return $this->first_name;
        } 

        function setLastName($lastName){
            $this->last_name = $lastName;
        }
        function getLastName(){
           return $this->last_name;
        } 

        function setUserProfile($userProfile){
            $this->user_profile = $userProfile;
        }
        function getUserProfile(){
           return $this->user_profile;
        } 

        function setUserClient($userClient){
            $this->user_client = $userClient;
        }
        function getUserClient(){
           return $this->user_client;
        }         
        
    };
?>