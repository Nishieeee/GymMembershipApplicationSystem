<?php 
    require_once __DIR__ . "../../config/Database.php";
    class Plan extends Database {

        public $plan_id = "";
        public $plan_name = "";
        public $description = "";
        public $duration_months = "";
        public $price = "";

        public function getAllPlans() {
            $sql = "SELECT * FROM membership_plans ORDER BY price ASC";

            $query = $this->connect()->prepare($sql);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }
        public function getUserPlan($user_id) {
            $sql = "SELECT p.plan_name, s.end_date, s.status FROM membership_plans p
            join subscriptions s on s.plan_id = p.plan_id
            where s.user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            
            if($query->execute()) {
                $rows = $query->fetch();
                if($rows) {
                    return $rows;
                } else {
                    return null;
                }
            } else {
                return null;
            }       
        }
        public function addNewPlan($plan) {
            $sql = "INSERT INTO `membership_plans`(`plan_name`, `description`, `duration_months`, `price`) VALUES (:plan_name, :description , :duration_months , :price)";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":plan_name", $plan['plan_name']);
            $query->bindParam(":description", $plan['description']);
            $query->bindParam(":duration_months", $plan['duration_months']);
            $query->bindParam(":price", $plan['price']);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }

    }

?>