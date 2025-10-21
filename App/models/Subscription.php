<?php
    require_once __DIR__ . "../../config/Database.php";

    class Subscription extends Database {

        public function subscripePlan($subData) {
        
            
            if(isset($subData['subscription_id'])) {
                $sql = "UPDATE subscriptions SET plan_id=:plan_id, start_date= :start_date, end_date= :end_date WHERE subscription_id = :subscription_id";
            } else {
                $sql = "INSERT INTO subscriptions(user_id, plan_id, start_date, end_date) VALUES (:user_id, :plan_id,:start_date, :end_date)";
            }
             
            $query = $this->connect()->prepare($sql);

            if(isset($subData['subscription_id'])) {          
                $query->bindParam(":subscription_id", $subData['subscription_id']);
            }
            if(!isset($subData['subscription_id'])) {
                $query->bindParam(":user_id", $subData['user_id']);
            }
            $query->bindParam(":plan_id", $subData['plan_id']);
            $query->bindParam(":start_date", $subData['start_date']);
            $query->bindParam(":end_date", $subData['end_date']);
            
            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
        public function cancelPlan($subscription_id) {
            $sql = "UPDATE subscriptions SET plan_id=:plan_id, start_date= :start_date, end_date= :end_date WHERE subscription_id = :subscription_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":subscription_id", $subscription_id);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function checkUserCurrentPlan($user_id) {
            $sql = "SELECT subscription_id FROM subscriptions WHERE user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetch(PDO::FETCH_ASSOC);
            } else {
                return null;
            }

        }
    }
?>
 
 
 
 
 
