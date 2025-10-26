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
        public function displayAllUsers() {
            $sql = "SELECT m.user_id, CONCAT(m.first_name, ' ', m.last_name) as name, m.email, m.created_at, p.plan_name, s.end_date, m.status FROM members m LEFT JOIN subscriptions s on s.user_id = m.user_id LEFT JOIN membership_plans p ON p.plan_id = s.plan_id WHERE role != 'admin' GROUP BY m.user_id  ORDER BY m.created_at DESC";

            $query = $this->connect()->prepare($sql);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }

        public function displayAllWalkInMembers() {
            $sql = "SELECT CONCAT(first_name, ' ', last_name) as name, email, contact_no, session_type, payment_amount, visit_time, end_date FROM walk_ins";

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
            $sql = "INSERT INTO `members`( `first_name`, `last_name`, `middle_name`, `email`, `date_of_birth` , `gender` , `password`, `role`, `created_at`) VALUES (:first_name , :last_name, :middle_name, :email, :date_of_birth, :gender, :password, 'member' , NOW())";
            $query = $this->connect()->prepare($sql);
            $query->bindParam(":first_name", $UserData['first_name']);
            $query->bindParam(":last_name", $UserData['last_name']);
            $query->bindParam(":middle_name", $UserData['middle_name']);
            $query->bindParam(":email", $UserData['email']);
            $query->bindParam(":date_of_birth", $UserData['date_of_birth']);
            $query->bindParam(":gender", $UserData['gender']);
            $query->bindParam(":password", $UserData['password']);
            
            if($query->execute()) {
                $sql = "SELECT user_id FROM members WHERE email = :email";

                $query = $this->connect()->prepare($sql);
                $query->bindParam(":email", $UserData['email']);

                if($query->execute()) {
                    return $query->fetch();
                } else {
                    return null;
                }
            } else {
                return null;
            }
        }
        public function addWalkinMember($userData) {
            $sql = "INSERT INTO walk_ins(first_name, last_name, middle_name, email, contact_no, session_type, payment_method, payment_amount, visit_time, end_date) VALUES (:first_name, :last_name, :middle_name, :email, :contact_no, :session_type, :payment_method ,:payment_amount, :visit_time, :end_date)";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":first_name", $userData['first_name']);
            $query->bindParam(":last_name", $userData['last_name']);
            $query->bindParam(":middle_name", $userData['middle_name']);
            $query->bindParam(":email", $userData['email']);
            $query->bindParam(":contact_no", $userData['contact_no']);
            $query->bindParam(":session_type", $userData['session_type']);
            $query->bindParam(":payment_method", $userData['payment_method']);
            $query->bindParam(":payment_amount", $userData['payment_amount']);
            $query->bindParam(":visit_time", $userData['visit_time']);
            $query->bindParam(":end_date", $userData['end_date']);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }

        } 
        
        public function getMemberData() {
            $user_id = $_GET['user_id'];

            $sql = "SELECT m.*, mp.plan_name FROM members m LEFT JOIN subscriptions s ON s.user_id = m.user_id LEFT JOIN membership_plans mp ON mp.plan_id = s.plan_id WHERE m.user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                echo json_encode([
                    'success' => true,
                    'data' => $query->fetch(),
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'Data' => NULL,
                ]);
            }
        }

        public function updatMember() {
            $sql = "";
        }
    }

?>