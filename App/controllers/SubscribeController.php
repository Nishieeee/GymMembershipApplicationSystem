<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Subscription.php";
    require_once __DIR__ . "/../models/Plan.php";

    class SubscribeController extends Controller {
        public function Subscribe() {
            session_start();
            $subscribe = new Subscription();
            $planModel = new Plan();
            $plans = $planModel->getAllActivePlans();
            $user_id = $_SESSION['user_id'];


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

                //check if user is already subscribe to the plan
                $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                $subscriptionDetails['subscription_id'] = $userCurrentPlan['subscription_id'];
                echo $subscriptionDetails['subscription_id'];
                if($userCurrentPlan) {
                    //show modal to user saying that if he agrees his old plan will be overwritten
                    
                    if(empty(array_filter($subscriptionError))) {
                        if($subscribe->subscripePlan($subscriptionDetails)) {
                            header("location: index.php?controller=Dashboard&action=member");
                            //also success pages
                        }
                    } else {
                        //create error pages
                    }

                } else {
                    if(empty(array_filter($subscriptionError))) {
                        if($subscribe->subscripePlan($subscriptionDetails)) {
                            header("location: index.php?controller=Dashboard&action=member");
                            //also success pages
                        }
                    } else {
                        //create error pages
                    }
                }
            }
        }
    }

?>