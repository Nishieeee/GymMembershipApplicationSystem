<?php 
    require_once __DIR__ . "/../config/Database.php";

    class Payment extends Database {

        public function openPayment($subData) {
            $sql = "INSERT INTO payments (subscription_id, amount, payment_date, status) VALUES (
            :subscription_id, :amount, :payment_date, :status)";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":subscription_id", $subData['subscription_id']);
            $query->bindParam(":amount", $subData['amount']);
            $query->bindParam(":payment_date", $subData['payment_date']);
            $query->bindParam(":status", $subData['status']);

            if($query->execute()){
                return true;
            } else {
                return false;
            }

        }

        public function getPaymentDetails($user_id) {
            $sql = "SELECT s.subscription_id, mp.plan_name, s.start_date, s.end_date, p.amount, p.payment_date, p.status FROM subscriptions s JOIN membership_plans mp ON mp.plan_id = s.plan_id
            JOIN payments p ON p.subscription_id = s.subscription_id
            WHERE s.user_id = :user_id ORDER BY status DESC";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetchAll();
            } else {
                return null;
            }
        }
    }
    

?>