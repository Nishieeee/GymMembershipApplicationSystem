<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Payment.php";
    require_once __DIR__ . "/../models/subscription.php";
    require_once __DIR__ . "/../models/Plan.php";


    class PaymentController extends Controller {

        private $userPaymentDetails;

        public function planPayment() {
            session_start();
            $paymentModel = new Payment();

            $user_id = $_SESSION['user_id'];
            $this ->userPaymentDetails = $paymentModel->getPaymentDetails($user_id);
            $this->view('payments', [
                "paymentDetails" => $this->userPaymentDetails,
            ]);
        }


    // 🔴 Action name MUST match the URL: &action=processPayment
    public function processPayment() {
        $paymentModel = new Payment();
        $paymentDetails = [
            "subscription_id" => "",
            "payment_id" => "",
            "payment_method" => "",
            "payment_status" => "",
            "remarks" => ""
        ];
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
            exit;
        }

        // 1. Get and Validate Data
        $paymentDetails['subscription_id'] = $_POST['subscription_id'] ?? "";
        $amount = $_POST['amount'] ?? "";
        $paymentDetails['payment_method'] = $_POST['payment_method'];
        $paymentDetails['payment_id'] = 13;
        $paymentDetails['transaction_type'] = "new";
        $paymentDetails['status'] = "paid";
            
        if (!$paymentDetails) {
            http_response_code(400); // Send 400 status for bad data
            echo json_encode(['success' => false, 'message' => 'Missing required payment data from the form.']);
            exit;
        }

        // --- 2. Call Model / Simulate Payment Logic (Same as before)
        $result = $paymentModel->completePayment($paymentDetails);

            
        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => $result['message'],
                'transaction_id' => $result['transaction_id']
            ]);
        }
        //  else {
        //     http_response_code(400); // Send 400 status for business logic failure
        //     echo json_encode([
        //         'success' => false,
        //         'message' => $result['message']
        //     ]);
        // }
        exit;
    }
}