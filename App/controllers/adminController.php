<?php 
    session_start();
    require_once __DIR__ . "../../config/Database.php";
    require_once __DIR__ . "../../models/User.php";
    require_once __DIR__ . "../../models/Plan.php";
    $userObj = new User();
    $userObj = new Plan();

    class Admin extends Database {
        public function displayAllUsers() {
            $sql = "select CONCAT(m.first_name, ' ', m.last_name) as name, m.email, m.created_at, p.plan_name, s.end_date, s.status from members m
            join subscriptions s on s.user_id = m.user_id
            join membership_plans p on p.plan_id = s.plan_id";
            
            $query = $this->connect()->prepare($sql);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }
        public function getAllPlans() {
            $sql = "SELECT * FROM membership_plans";

            $query = $this->connect()->prepare($sql);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
            
        }
    };

?>