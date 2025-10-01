<?php 
    require_once "../config/Database.php";
    class Member extends Database {
        public $user_id = "";
        public $first_name = "";
        public $last_name = "";
        public $middle_name = "";
        public $email = "";
        public $password = "";
        public $role = "";
        public $created_at = "";

        public function register() {

        }
        public function login() {
            
        }
    }

?>