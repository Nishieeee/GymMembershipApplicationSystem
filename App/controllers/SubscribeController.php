<?php 
    require_once __DIR__ . "/../Controller.php";
    //models
    require_once __DIR__ . "/../models/Subscription.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../models/Payment.php";
    require_once __DIR__ . "/../models/notification.php";
    //helper
    require_once __DIR__ . "/../helpers/notificationHelper.php";
    
    class SubscribeController extends Controller {
        
        public function Subscribe() {
            $this->requireLogin();
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            $subscribe = new Subscription();
            $planModel = new Plan();
            $paymentModel = new Payment();
            $userModel = new User();
            
            $user_id = $_SESSION['user_id'];
            
            // Initialize details arrays
            $subscriptionDetails = [
                "subscription_id" => "", "user_id" => "", "plan_id" => "",
                "start_date" => "", "end_date" => ""
            ];
            $subscriptionError = []; 
            
            $paymentDetails = [
                "subscription_id" => "", "amount" => "", 
                "payment_date" => "", "status" => ""
            ];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $subscriptionDetails['user_id'] = $user_id;
                $subscriptionDetails['plan_id'] = trim(htmlspecialchars($_POST['plan_id']) ?? "");
                $subscriptionDetails['start_date'] = date("Y-m-d");
                $subscriptionDetails['end_date'] = date('Y-m-d', strtotime('+30 days'));

                // 1. Basic Validation
                if (empty($subscriptionDetails['user_id'])) $subscriptionError[] = "User not logged in.";
                if (empty($subscriptionDetails['plan_id'])) $subscriptionError[] = "Please Select a plan.";

                // If basic validation fails, stop here
                if (!empty($subscriptionError)) {
                    $this->view('subscription_failed', ['error_message' => implode(', ', $subscriptionError)]);
                    return;
                }

                // ============================================================
                // 2. NEW: Check for Pending Payments
                // ============================================================
                $pendingResult = $paymentModel->getUserPendingPayments($user_id);
                
                // Since your function uses fetch(), it returns an array (e.g., [0 => '3'])
                // We need to extract the actual number from the first column
                $pendingCount = 0;
                if (is_array($pendingResult)) {
                    $pendingCount = $pendingResult[0] ?? 0;
                }

                if ($pendingCount > 0) {
                    // Stop execution and show failure page
                    $this->feedback('subscription_failed', [
                        'error_message' => "You have pending payments. Please settle your outstanding balance before subscribing to a new plan."
                    ]);
                    return;
                }
                // ============================================================

                // 3. Proceed with Subscription Logic
                $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                
                if ($userCurrentPlan) {
                    // --- SCENARIO A: Upgrade/Change Plan (Overwrite old plan) ---
                    if ($subscribe->subscripePlan($subscriptionDetails)) {
                        
                        if ($subscribe->cancelPlan($userCurrentPlan['subscription_id'])) { 
                            $newUserPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']); 
                            $planInfo = $planModel->getPlanById($subscriptionDetails['plan_id']); 

                            // Setup Payment
                            $paymentDetails['subscription_id'] = $newUserPlan['subscription_id'];
                            $paymentDetails['amount'] = $planInfo['price']; 
                            $paymentDetails['payment_date'] = date('Y-m-d'); 
                            $paymentDetails['status'] = "pending";

                            if ($paymentModel->openPayment($paymentDetails)) {
                                NotificationHelper::membershipRenewed($user_id, $subscriptionDetails['end_date']);
                                $this->feedback('subscription_success');
                            } else {
                                $this->feedback('subscription_failed', ['error_message' => "Error setting up payment."]);
                            }
                        } else {
                            $this->feedback('subscription_failed', ['error_message' => "Could not cancel previous plan."]);
                        }
                    } else {
                        $this->feedback('subscription_failed', ['error_message' => "Database error while subscribing."]);
                    }

                } else {
                    // --- SCENARIO B: New Subscription ---
                    if ($subscribe->subscripePlan($subscriptionDetails)) {
                        $newUserPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                        $planInfo = $planModel->getPlanById($subscriptionDetails['plan_id']);

                        $paymentDetails['subscription_id'] = $newUserPlan['subscription_id'];
                        $paymentDetails['amount'] = $planInfo['price'];
                        $paymentDetails['payment_date'] = date('Y-m-d');
                        $paymentDetails['status'] = "pending";

                        if ($paymentModel->openPayment($paymentDetails)) {
                            // $this->notifySubscription($user['email'], $user['name']); 
                            $this->feedback('subscription_success');
                        } else {
                            $this->feedback('subscription_failed', ['error_message' => "Error setting up payment."]);
                        }
                    } else {
                        $this->feedback('subscription_failed', ['error_message' => "Database error while subscribing."]);
                    }
                }
            }
        }

        public function CancelSubscription() {
            $this->requireLogin();
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $subscriptionModel = new Subscription();
            $notificationModel = new Notification();

            $user_id = $_SESSION['user_id'];
            
            // Check if user actually has a plan
            $userPlan = $subscriptionModel->checkUserCurrentPlan($user_id);
            
            if (!$userPlan) {
                $this->feedback('cancel_failed', ['error_message' => "Cancel failed, No active plan."]);
                header("location: index.php?controller=Dashboard&action=member");
                exit();
            }

            // --- LOGIC SPLIT: GET vs POST ---

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // 1. Process the Cancellation
                if (isset($_POST['confirm_cancel']) && $_POST['confirm_cancel'] === 'true') {
                    
                    $subscription_id = $userPlan['subscription_id'];

                    if ($subscriptionModel->cancelPlan($subscription_id)) {
                        
                        // Create Notification
                        $notificationModel->create(
                            $user_id, 
                            "Plan Cancellation", 
                            "Your Current Plan has been Cancelled.", 
                            "warning", 
                            "membership"
                        );

                        // Show Success View
                        $this->feedback('cancel_success');

                    } else {
                        // Show Failure View
                        $this->feedback('cancel_failed', ['error_message' => "Database error occurred while cancelling."]);
                    }
                } else {
                    // Invalid POST request (missing confirmation token)
                    header("location: index.php?controller=Dashboard&action=member");
                }

            } else {
                // 2. Show the "Are you sure?" Page (GET Request)
                
                // Pass plan details to the feedback so user knows what they are cancelling
                $this->feedback('cancel_confirmation', [
                    'plan_name' => $userPlan['plan_name'] ?? 'Membership', // Adjust key based on your DB
                    'subscription_id' => $userPlan['subscription_id']
                ]);
            }
        }

        public function expirePlan() {
            $this->requireLogin();
            session_start();
            $user_id = $_SESSION['user_id'];

            $userModel = new User();
            $planModel = new Plan();
            $notificationModel = new Notification();

            $user = $userModel->getMember($user_id);
            $userPlan = $planModel->getUserPlan($user_id);
            $subscriptionModel = new Subscription();
            $userCurrentPlan = $subscriptionModel->checkUserCurrentPlan($_SESSION['user_id']);
            $subscription_id = $userCurrentPlan['subscription_id'];
            if($subscriptionModel->expirePlan($subscription_id)) {
                $userModel->deleteMemberViaId($user_id);
                $userPlan['status'] = 'expired';
                //email user of expired subscription
                $this->notifyExpired($user['email'], $user['name']);
                NotificationHelper::membershipExpired($user_id);
                $notificationModel->create($user_id, "Plan Expiration", "Your Current Plan has Expired", "warning", "membership");

                $this->view('dashboard', [
                    'userInfo' => $user,
                    'userPlan' => $userPlan,
                ]);
            } else {
                $this->view('dashboard', [
                    'userInfo' => $user,
                    'userPlan' => $userPlan,
                ]);
            }
        }

        
        public function notifyExpired($email, $name) {
            $mail = $this->mailer(); 
            $mail->addAddress($email, $name);
            $mail->Subject = "Subscription Expired";
            $mail->isHTML(true);
            $mail->Body = "
                <h3>Hello, $name</h3>
                <p>Your subscription has expired.</p>
                <p><a href='https://gymazing.com/renew'>Renew now</a> to continue enjoying our services!</p>
                <br>
                <p>Thank you!</p>
            ";
            $mail->AltBody = "Hi $name, your subscription has expired. Please renew your plan.";
            $mail->send();
        }

        public function notifySubscription($email, $name) {
            $mail = $this->mailer();
            $mail->addAddress($email, $name);
            $mail->Subject = "Subscription Activated Successfully!";
            $mail->isHTML(true);
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f6f9;'>
                    <div style='max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px;'>
                        <h2 style='color: #4CAF50;'>Welcome to Our Service! 🌟</h2>

                        <p>Hi <strong>{$name}</strong>,</p>
                        <p>We're excited to let you know that your subscription has been <strong>successfully activated</strong>!</p>

                        <div style='margin: 20px 0; padding: 15px; background-color: #e8f5e9; border-left: 4px solid #4CAF50;'>
                            <p style='margin: 0; font-size: 15px;'>You now have full access to all premium features.</p>
                        </div>

                        <p>Start exploring now and enjoy the experience! </p>

                        <a href='https://your-website.com/dashboard' style='display:inline-block; margin-top: 15px; background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Go to Dashboard</a>

                        <hr style='margin-top: 30px;'>

                        <p style='font-size: 12px; color: #777;'>If you have any questions, feel free to reach out to our support team.</p>
                        <p style='font-size: 12px; color: #777;'>&copy; " . date('Y') . " Gymazing!. All rights reserved.</p>
                    </div>
                </div>
            ";
            $mail->AltBody = "Hi {$name}, your subscription was successfully activated! Enjoy full access now.";
            $mail->send();
        }

        public function notifyUpgradedPlan($email, $name) {
            
        }
    }

?>