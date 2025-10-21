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
            $sql = "SELECT * FROM payments WHERE payment_id = 1";

            $query = $this->connect()->prepare($sql);


            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }
    }
    

?>