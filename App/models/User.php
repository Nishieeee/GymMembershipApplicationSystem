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

        public function getAllMembers() {
            $sql = "SELECT user_id FROM members";

            $query = $this->connect()->prepare($sql);


            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }
        public function findByEmail($email) {
            $sql = "SELECT user_id, role, email, password FROM members WHERE email = :email";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":email", $email);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }

        }
        public function getMember($user_id) {
            $sql = "SELECT CONCAT(first_name, ' ', last_name) as name, first_name, email, role, created_at FROM members WHERE user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }
        public function getMemberSubcription($user_id) {
            $sql = "select CONCAT(m.first_name, ' ', m.last_name) as name, m.phone_no, m.created_at, p.plan_name, s.end_date, s.status from members m
            join subscriptions s on s.user_id = m.user_id
            join membership_plans p on p.plan_id = s.plan_id
            where m.user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }
        public function addMember(array $UserData) {
            $sql = "INSERT INTO `members`( `first_name`, `last_name`, `middle_name`, `email`, `age` , `gender` , `password`, `role`, `created_at`) VALUES (:first_name , :last_name, :middle_name, :email, :age, :gender, :password1, 'member' , NOW())";
            $query = $this->connect()->prepare($sql);
            $query->bindParam(":first_name", $UserData['first_name']);
            $query->bindParam(":last_name", $UserData['last_name']);
            $query->bindParam(":middle_name", $UserData['middle_name']);
            $query->bindParam(":email", $UserData['email']);
            $query->bindParam(":age", $UserData['age']);
            $query->bindParam(":gender", $UserData['gender']);
            $query->bindParam(":password1", $UserData['password']);
            
            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
        
    }

?>