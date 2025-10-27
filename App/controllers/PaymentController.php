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

    public function processPayment() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            exit;
        }

        // Initialize model
        $paymentModel = new Payment();
        $payment_id = $paymentModel->getPaymentId($_POST['subscription_id']);
        // Validate and gather inputs
        $paymentDetails = [
            "subscription_id" => $_POST['subscription_id'] ?? null,
            "payment_id" => $payment_id['payment_id'] ?? $_POST['payment_id'],
            "payment_method" => $_POST['payment_method'] ?? null,
            "payment_status" => "completed",
            "transaction_type" => "new",
            "remarks" => $_POST['remarks'] ?? ""
        ];

        // Ensure required data
        if (empty($paymentDetails['subscription_id']) || empty($paymentDetails['payment_method'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required payment fields']);
            exit;
        }

        // Process payment
        $result = $paymentModel->completePayment($paymentDetails);

        // Respond accordingly
        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Transaction completed successfully'
            ]);
        } else {
             http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Transaction failed'
            ]);
        }
    }

}