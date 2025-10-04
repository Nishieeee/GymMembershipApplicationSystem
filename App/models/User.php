<?php 
    require_once "../";
    

    class User extends Database {
        public $user_id = "";
        public $first_name = "";
        public $last_name = "";
        public $middle_name = "";
        public $email = "";
        public $password = "";
        public $role = "";
        public $created_at = "";

        protected $db;

        public function findByEmail($email) {
            $sql = "SELECT * FROM members WHERE email = :email";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":email", $email);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }

        }

        
    }

?>