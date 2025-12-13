<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Payment.php";
    require_once __DIR__ . "/../models/subscription.php";
    require_once __DIR__ . "/../models/Plan.php";

    require_once __DIR__ . "/../helpers/notificationHelper.php";


    class PaymentController extends Controller {

        private $userPaymentDetails;

        public function planPayment() {
            $this->requireLogin();
            $paymentModel = new Payment();

            $user_id = $_SESSION['user_id'];
            $this ->userPaymentDetails = $paymentModel->getPaymentDetails($user_id);
            $this->view('payments', [
                "paymentDetails" => $this->userPaymentDetails,
            ]);
        }

    public function processPayment() {
        $this->requireLogin();
        session_start();
        $user_id = $_SESSION['user_id'];
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
            // $this->notifyPaymentSuccess();
            $paymentModel = new Payment();
            $userModel = new User();

            $payment_details = $paymentModel->getPaymentId($paymentDetails['subscription_id']);
            $userDetails = $userModel->getMember($user_id);
            NotificationHelper::paymentReceived($user_id, $payment_details['amount']);
            NotificationHelper::paymentReceived_Admin(7, $userDetails['name'], $payment_details['amount']);
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

    public function notifyPaymentSuccess($email, $name, $amount, $plan_name, $transactionId) {
        $mail = $this->mailer();
        $mail->addAddress($email, $name);
        $mail->Subject = "Payment Successful — Thank You! 💳";
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f7f9fc;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; border: 1px solid #e1e8ee;'>
                    
                    <h2 style='color: #2C7DFA; margin-bottom: 10px;'>Payment Confirmation</h2>

                    <p>Hi <strong>{$name}</strong>,</p>
                    <p>We're happy to let you know that your payment was processed successfully! 🎉</p>

                    <div style='margin: 20px 0; padding: 15px; background-color: #eaf3ff; border-left: 4px solid #2C7DFA;'>
                        <p style='margin: 0; font-size: 15px;'>
                            ⭐ Your subscription is now fully activated and ready to use.
                        </p>
                    </div>

                    <p>Details of your payment:</p>
                    <ul style='color: #333;'>
                        <li><strong>Amount:</strong> {$amount}</li>
                        <li><strong>Subscription Plan:</strong> {$plan_name}</li>
                        <li><strong>Transaction ID:</strong> {$transactionId}</li>
                        <li><strong>Date:</strong> " . date('F j, Y') . "</li>
                    </ul>

                    <p>To explore features and manage your subscription, click below:</p>

                    <a href='https://your-website.com/dashboard' 
                    style='display:inline-block; margin-top: 15px; background-color: #2C7DFA; 
                    color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px;'>
                    Go to Dashboard
                    </a>

                    <hr style='margin-top: 30px;'>
                    <p style='font-size: 12px; color: #777;'>If you didn't make this transaction, please contact our support immediately.</p>
                    <p style='font-size: 12px; color: #777;'>&copy; " . date('Y') . " Your Company. All rights reserved.</p>
                </div>
            </div>
        ";
        $mail->AltBody = "Hi {$name}, your payment was successful! Thanks for your purchase.";

    }

}