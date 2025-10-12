<?php 
    session_start();
    require_once "../config/Database.php";
    include_once "./models/User.php";


    class Admin extends Database {
        public function displayAllUsers() {
            
        }
    };

?>