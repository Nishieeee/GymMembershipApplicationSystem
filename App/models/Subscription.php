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

        public function getUserPayments() {
            $sql = "SELECT 
            CONCAT(m.first_name, ' ', m.last_name) as name, pt.transaction_id, mp.plan_name, p.amount, p.payment_date, p.status
            FROM members m
            LEFT JOIN subscriptions s ON s.user_id = m.user_id
            LEFT JOIN membership_plans mp ON mp.plan_id = s.plan_id
            LEFT JOIN payments p ON p.subscription_id = s.subscription_id
            LEFT JOIN payment_transaction pt ON pt.payment_id = p.payment_id
            WHERE m.role = 'member'
            ORDER BY p.payment_date DESC";
            // WHERE p.status = 'pending'

            $query = $this->connect()->prepare($sql);
            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }

        public function countTotalPayments() {
            $sql = "SELECT COUNT(*) as total_number_of_payments FROM payments";
            $query = $this->connect()->prepare($sql);
            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }
    }
?>
 
 
 
 
 
