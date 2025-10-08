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


    }

?>