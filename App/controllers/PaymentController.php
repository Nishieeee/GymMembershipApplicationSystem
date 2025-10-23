<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Payment.php";
    require_once __DIR__ . "/../models/subscription.php";
    require_once __DIR__ . "/../models/Plan.php";


    class PaymentController extends Controller {
        public function planPayment() {
            session_start();
            $paymentModel = new Payment();
            $planModel = new Plan();

            $user_id = $_SESSION['user_id'];
            $paymentDetails = $paymentModel->getPaymentDetails($user_id);
            $this->view('payments', [
                "paymentDetails" => $paymentDetails,
            ]);
        }
    }


?>