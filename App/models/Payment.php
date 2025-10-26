<?php 
    require_once __DIR__ . "/../config/Database.php";

    class Payment extends Database {

        private $last_transaction_id = "";

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
            $sql = "SELECT s.subscription_id, mp.plan_name, s.start_date, s.end_date, p.amount, p.payment_id, p.payment_date, p.status FROM subscriptions s JOIN membership_plans mp ON mp.plan_id = s.plan_id
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

        public function completePayment($paymentDetails) {
            $sql = "INSERT INTO payment_transaction( subscription_id, payment_id, payment_method, transaction_type, payment_status, remarks) VALUES (:subscription_id, :payment_id, :payment_method, :transaction_type, :payment_status, :remarks)";
            
            $query = $this->connect()->prepare($sql);
            $query->bindParam(":subscription_id", $paymentDetails['subscription_id']);
            $query->bindParam(":payment_id", $paymentDetails['payment_id']);
            $query->bindParam(":payment_method", $paymentDetails['payment_method']);
            $query->bindParam(":transaction_type", $paymentDetails['transaction_type']);
            $query->bindParam(":payment_status", $paymentDetails['payment_status']);
            $query->bindParam(":remarks", $paymentDetails['remarks']);
            if($query->execute()) {
                $this->last_transaction_id = $this->connect()->lastInsertId();

                if($this->markPaid($paymentDetails['payment_id'])) {
                    echo json_encode([
                        'success'=> true,
                        'messsage' => "transaction success",
                        'transaction_id' => $this->last_transaction_id

                    ]);
                } else {
                    echo json_encode([
                        'success'=> true,
                        'messsage' => "transaction failed",         
                    ]);
                }              
            } else {
                echo json_encode([
                    'success'=> true,
                    'messsage' => "transaction failed",         
                ]);
            }
        }

        public function markPaid($payment_id) {
            $sql = "UPDATE payments SET status= 'paid' WHERE payment_id = :payment_id";

            $query = $this->connect()->prepare($sql);

            $query->bindParam(":payment_id", $payment_id);

            if($query->execute()) {
                return true;
            } else {
                return false;
            }
        }
    }
    

?>