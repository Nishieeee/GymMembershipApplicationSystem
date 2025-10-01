<?php 
    namespace App\config;
    
    class Database {
        private $host = "locahost";
        private $user = "root";
        private $password = "";
        private $dbname = "gym";

        protected $conn;

        public function connect() {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password);

            return $this->conn;
        }
    }

?>