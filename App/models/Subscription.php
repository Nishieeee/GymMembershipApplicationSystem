<?php
    require_once __DIR__ . "../../config/Database.php";

    class Subscription extends Database {

        public function subscripePlan($subData) {   
            
            $sql = "INSERT INTO subscriptions(user_id, plan_id, start_date, end_date) VALUES (:user_id, :plan_id,:start_date, :end_date)";
             
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
        public function cancelPlan($subscription_id) {
            $sql = "UPDATE subscriptions SET status = 'cancelled' WHERE subscription_id = :subscription_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":subscription_id", $subscription_id);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function expirePlan($subscription_id) {
            $sql = "UPDATE subscriptions SET status = 'expired' WHERE subscription_id = :subscription_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":subscription_id", $subscription_id);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }

        public function checkUserCurrentPlan($user_id) {
            $sql = "SELECT subscription_id FROM subscriptions WHERE user_id = :user_id AND status = 'active'";

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
 
 
 
 
 
