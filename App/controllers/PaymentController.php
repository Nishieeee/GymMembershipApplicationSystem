<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Payment.php";


    class PaymentController extends Controller {
        public function planPayment() {
            session_start();
            $paymentModel = new Payment();
            $user_id = $_SESSION['user_id'];
            $paymentDetails = $paymentModel->getPaymentDetails($user_id);

            $this->view('payments', [
                "paymentDetails" => $paymentDetails,
            ]);
        }
    }


?>