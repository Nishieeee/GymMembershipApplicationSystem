<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Subscription.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../models/Payment.php";
    class SubscribeController extends Controller {
        
        public function Subscribe() {
            session_start();
            $subscribe = new Subscription();
            $planModel = new Plan();
            $paymentModel = new Payment();
            $userModel = new User();
            $user_id = $_SESSION['user_id'];

            //subscription details
            $subscriptionDetails = [
                "subscription_id" => "",
                "user_id" => "",
                "plan_id" => "",
                "start_date" => "",
                "end_date" => "",
            ];
            $subscriptionError = [
                "user_id" => "",
                "plan_id" => "",
                "start_date" => "",
                "end_date" => "",
            ];

            //payment details
            $paymentDetails = [
                "subscription_id" => "",
                "amount" => "",
                "payment_date" => "",
                "status" => "",
            ];
            $paymentError = "";

            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $subscriptionDetails['user_id'] = $user_id;
                $subscriptionDetails['plan_id'] = trim(htmlspecialchars($_POST['plan_id']) ?? "");
                $subscriptionDetails['start_date'] = date("Y-m-d");

                $subscriptionDetails['end_date'] =  date('y-m-d', strtotime('+30 days'));
                if(empty($subscriptionDetails['user_id'])) {
                    $subscriptionError['user_id'] = "User not logged in.";
                }
                if(empty($subscriptionDetails['plan_id'])) {
                    $subscriptionError['plan_id'] = "Please Select a plan.";
                }

                //check if user is already subscribe to a plan
                $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']) ?? "";
                
                if($userCurrentPlan) {
                    //show modal to user saying that if he agrees his old plan will be overwritten                
                    if(empty(array_filter($subscriptionError))) {
                        if($subscribe->subscripePlan($subscriptionDetails)) {

                            //cancell old plan
                            if($subscribe->cancelPlan($subscriptionDetails['subscription_id'])) {
                                $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                                $userPlan = $planModel->getUserPlan($subscriptionDetails['user_id']);

                                //fill up payment details
                                $paymentDetails['subscription_id'] = $userCurrentPlan['subscription_id'];
                                $paymentDetails['amount'] = $userPlan['price'];
                                $paymentDetails['payment_date'] = $userPlan['end_date'];
                                $paymentDetails['status'] = "pending";
                                if($paymentModel->openPayment($paymentDetails)) {
                                    header("location: index.php?controller=Dashboard&action=member");
                                    //also success pages
                                } else {
                                    $paymentError = "Error with setting up payment";
                                    //create error pages
                                }
                            }
                            // handle case if cancelling of old plan doesn't work       
                        }
                    } else {
                        //create error pages
                    }
                } else {
                    if(empty(array_filter($subscriptionError))) {
                        if($subscribe->subscripePlan($subscriptionDetails)) {
                            $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                            $userPlan = $planModel->getUserPlan($subscriptionDetails['user_id']);
                            
                            //fill up payment details
                            $paymentDetails['subscription_id'] = $userCurrentPlan['subscription_id'];
                            $paymentDetails['amount'] = $userPlan['price'];
                            $paymentDetails['payment_date'] = $userPlan['end_date'];
                            $paymentDetails['status'] = "pending";
                            if($paymentModel->openPayment($paymentDetails)) {
                                $user = $userModel->getMember($user_id);
                                $this->notifySubscription($user['email'], $user['name']);
                                header("location: index.php?controller=Dashboard&action=member");
                                //also success pages
                            } else {
                                echo "Error With Payment";
                                //create error pages
                            }

                        }   
                    } else {
                        //create error pages
                    }
                }
            }
        }

        public function CancelSubscription() {
            session_start();

            $subscriptionModel = new Subscription();
            
            $userPlan = $subscriptionModel->checkUserCurrentPlan($_SESSION['user_id']);
            $subscription_id = $userPlan['subscription_id'];
            echo $subscription_id;
            if($subscriptionModel->cancelPlan($subscription_id)) {
                header("location: index.php?controller=Dashboard&action=member");
            } else {
                //show error
            }
        }

        public function expirePlan() {
            session_start();
            $user_id = $_SESSION['user_id'];

            $userModel = new User();
            $planModel = new Plan();

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
    }

?>