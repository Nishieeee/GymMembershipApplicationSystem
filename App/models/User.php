<?php 
    require_once __DIR__ . "../../config/Database.php";
    

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
        public function getMember($user_id) {
            $sql = "SELECT * FROM members WHERE user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }

        public function addMember(array $UserData) {
            $sql = "INSERT INTO `members`( `first_name`, `last_name`, `middle_name`, `email`, `password`, `role`, `created_at`) VALUES (:first_name , :last_name, :middle_name, :email, :password1,'member', NOW())";
            $query = $this->connect()->prepare($sql);
            $query->bindParam(":first_name", $UserData['first_name']);
            $query->bindParam(":last_name", $UserData['last_name']);
            $query->bindParam(":middle_name", $UserData['middle_name']);
            $query->bindParam(":email", $UserData['email']);
            $query->bindParam(":password1", $UserData['password']);
            
            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
        
    }

?>