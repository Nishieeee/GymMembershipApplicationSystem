<?php 
    session_start();
    require_once __DIR__ . "../../config/Database.php";


    class Admin extends Database {
        private $userModel = "";
        private $planModel = "";

        public function __construct($userModel, $planModel) {
            $this->userModel = $userModel;
            $this->planModel = $planModel;
        }

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
            $sql = "SELECT * FROM membership_plans order by status";

            $query = $this->connect()->prepare($sql);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }  
        }
        public function addPlan($planData) {
            $this->planModel->addNewPlan($planData);
        }
        
    };

?>