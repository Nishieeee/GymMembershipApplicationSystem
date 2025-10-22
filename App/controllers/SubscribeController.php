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
                $userPlan['status'] = 'expired';
                $this->view('dashboard', [
                    'userInfo' => $user,
                    'userPlan' => $userPlan,
                ]);
            }
        }
    }

?>