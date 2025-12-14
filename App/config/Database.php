<?php 
    
    class Database {
        private $host = "localhost";
        private $user = "u194078580_clein";
        private $password = "Yukinoshita@002";
        private $dbname = "u194078580_gym";

        protected $conn;

        public function connect() {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);

            return $this->conn;
        }
    }

?>
