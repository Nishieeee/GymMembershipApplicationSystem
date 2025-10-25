<?php 

    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";

    class AdminController extends Controller {

        public function dashboard() {
            session_start();
            $user_id = $_SESSION['user_id'];

            $user = new User();
            $plan = new Plan();

            $members = $user->displayAllUsers();
            $walk_ins = $user->displayAllWalkInMembers();
            $plans = $plan->getAllPlans();

            $this->adminView('dashboard', [
                'walk_ins' => $walk_ins,
                'members' => $members,
                'plans' => $plans,
            ]);
        } 

        public function addPlan() {
            $user = new User();
            $plan = new Plan();

            $members = $user->displayAllUsers();
            $plans = $plan->getAllPlans();

            $planData = [
                "plan_name" => "",
                "description" => "",
                "duration_months" => "",
                "price" => "",
            ];
            
            $planErrors = [
                "plan_name" => "",
                "description" => "",
                "duration_months" => "",
                "price" => "",
            ];
            
        
            
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $planData['plan_name'] = trim(htmlspecialchars($_POST['plan_name'] ?? ''));
                $planData['description'] = trim(htmlspecialchars($_POST['description'] ?? ''));
                $planData['duration_months'] = trim(htmlspecialchars($_POST['duration_months'] ?? ''));
                $planData['price'] = trim(htmlspecialchars($_POST['price'] ?? ''));
                
                var_dump($planData);
                // Validate plan_name
                if(empty($planData['plan_name'])) {
                    $planErrors['plan_name'] = "Plan name is required";
                } elseif(strlen($planData['plan_name']) < 3) {
                    $planErrors['plan_name'] = "Plan name must be at least 3 characters";
                } elseif(strlen($planData['plan_name']) > 50) {
                    $planErrors['plan_name'] = "Plan name must not exceed 50 characters";
                }
            
                // Validate description
                if(empty($planData['description'])) {
                    $planErrors['description'] = "Description is required";
                } elseif(strlen($planData['description']) < 10) {
                    $planErrors['description'] = "Description must be at least 10 characters";
                } elseif(strlen($planData['description']) > 500) {
                    $planErrors['description'] = "Description must not exceed 500 characters";
                }
            
                // Validate duration_months
                if(empty($planData['duration_months'])) {
                    $planErrors['duration_months'] = "Duration is required";
                } elseif(!is_numeric($planData['duration_months'])) {
                    $planErrors['duration_months'] = "Duration must be a number";
                } elseif($planData['duration_months'] <= 0) {
                    $planErrors['duration_months'] = "Duration must be greater than 0";
                } elseif($planData['duration_months'] > 60) {
                    $planErrors['duration_months'] = "Duration must not exceed 60 months";
                }
            
                // Validate price
                if(empty($planData['price'])) {
                    $planErrors['price'] = "Price is required";
                } elseif(!is_numeric($planData['price'])) {
                    $planErrors['price'] = "Price must be a number";
                } elseif($planData['price'] < 0) {
                    $planErrors['price'] = "Price cannot be negative";
                } elseif($planData['price'] > 9999.99) {
                    $planErrors['price'] = "Price exceeds maximum allowed";
                }
            
                if(empty(array_filter($planErrors))) {
                    if($plan->addNewPlan($planData)) {
                        //reset form
                         $planData = [
                            "plan_name" => "",
                            "description" => "",
                            "duration_months" => "",
                            "price" => "",
                        ];
                        $this->adminView('dashboard', [
                            'members' => $members,
                            'plans' => $plans,
                            'planData' => $planData,
                        ]);
                    } else {
                        echo "failed";
                    }
                } else {
                    $state = true;
                    $this->adminView('dashboard', [
                        'members' => $members,
                        'plans' => $plans,
                        'planData' => $planData,
                        'planErrors' => $planErrors,
                        'openModal' => $state,
                    ]);
                }
            }
        }
    }


?>