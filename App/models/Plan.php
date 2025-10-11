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
                if(empty($query->fetch())) {
                    return null;
                } else {
                    return $query->fetch();
                }
            } else {
                return "failed";
            }
        }


    }

?>