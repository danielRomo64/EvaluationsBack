<?php   

    class ConnectionDB {

        private  $conn;
        private  $host = "";
        private  $nameDB = ""; 
        private  $user = "";  
        private  $password = "" ;                     

        function __construct($host, $nameDB, $user, $password){
            $this->host = $host;
            $this->nameDB = $nameDB;
            $this->user = $user;
            $this->password = $password;
        }   
        
        public function connect (){

          $this->conn = new PDO("mysql:host=".$this->host."; dbname=".$this->nameDB."; charset=utf8", $this->user, $this->password);  
          return $this->conn;
          
        }
    };
?>