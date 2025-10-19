<?php
    require_once __DIR__ . "../../config/Database.php";

    class Subscription extends Database {

        public function subscripePlan($subData) {
            $sql = "INSERT INTO subscriptions( user_id, plan_id, start_date, end_date) VALUES (:user_id, :plan_id, :start_date, :end_date)";
            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $subData['user_id']);
            $query->bindParam(":plan_id", $subData['plan_id']);
            $query->bindParam(":start_date", $subData['start_date']);
            $query->bindParam(":end_date", $subData['end_date']);
            

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
?>
 
 
 
 
 
