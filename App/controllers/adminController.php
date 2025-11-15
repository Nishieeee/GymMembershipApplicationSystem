<?php 

    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../models/Subscription.php";
    require_once __DIR__ . "/../models/Payment.php";
    require_once __DIR__ . "/../models/Trainer.php";

    class AdminController extends Controller {

        public function dashboard() {
            session_start();
            $user_id = $_SESSION['user_id'];

            $user = new User();
            $plan = new Plan();
            $payment = new Payment();
            $subscription = new Subscription();
            $trainerModel = new Trainer();

            $members = $user->displayAllUsers();
            $memberCount = $user->countActiveMembers();
            $totalEarned = $payment->totalEarned();
            $trainers = $trainerModel->getAllTrainers();
            $paymentDetails = $subscription->getUserPayments();
            $totalPayments = $subscription->countTotalPayments();
            $walk_ins = $user->displayAllWalkInMembers();
            $plans = $plan->getAllPlans();
            $activePlans = $plan->getAllActivePlans();

            $this->adminView('dashboard', [
                'memberCount' => $memberCount,
                'totalEarned' => $totalEarned,
                'paymentDetails' => $paymentDetails,
                'totalPayments' =>  $totalPayments,
                'trainers' => $trainers,
                'walk_ins' => $walk_ins,
                'members' => $members,
                'plans' => $plans,
                'activePlans' => $activePlans,
            ]);
        } 
        public function reports() {
            session_start();
            $user_id = $_SESSION['user_id'];

            $user = new User();
            $plan = new Plan();
            $payment = new Payment();
            $subscription = new Subscription();
            $trainerModel = new Trainer();

            $members = $user->displayAllUsers();
            $memberCount = $user->countActiveMembers();
            $totalEarned = $payment->totalEarned();
            $trainers = $trainerModel->getAllTrainers();
            $paymentDetails = $subscription->getUserPayments();
            $totalPayments = $subscription->countTotalPayments();
            $walk_ins = $user->displayAllWalkInMembers();
            $plans = $plan->getAllPlans();
            $activePlans = $plan->getAllActivePlans();

            $last12MonthsRevenue = $payment->getLast12MonthsRevenue();
            $dailyRevenue30Days = $payment->getDailyRevenueLast30Days();
            $revenueByPlan = $payment->getRevenueByPlan();
            $paymentStats = $payment->getPaymentStats();
            $pendingPayments = $payment->getPendingPayments();
            $paymentMethodStats = $payment->getPaymentMethodStats();

            $memberGrowth = $user->getMemberGrowthLast12Months();
            $activeInactiveCount = $user->getActiveInactiveCount();
            $membersByPlan = $user->getMembersByPlan();
            $retentionRate = $user->getRetentionRate();

            $expiringSubscriptions = $subscription->getExpiringSubscriptions(7);
            $subscriptionStatusBreakdown = $subscription->getSubscriptionStatusBreakdown();

            $this->adminView('reports', [
                'memberCount' => $memberCount,
                'totalEarned' => $totalEarned,
                'paymentDetails' => $paymentDetails,
                'totalPayments' =>  $totalPayments,
                'trainers' => $trainers,
                'walk_ins' => $walk_ins,
                'members' => $members,
                'plans' => $plans,
                'activePlans' => $activePlans,

               'last12MonthsRevenue' => $last12MonthsRevenue,
                'dailyRevenue30Days' => $dailyRevenue30Days,
                'revenueByPlan' => $revenueByPlan,
                'paymentStats' => $paymentStats,
                'pendingPayments' => $pendingPayments,
                'paymentMethodStats' => $paymentMethodStats,
                'memberGrowth' => $memberGrowth,
                'activeInactiveCount' => $activeInactiveCount,
                'membersByPlan' => $membersByPlan,
                'retentionRate' => $retentionRate,
                'expiringSubscriptions' => $expiringSubscriptions,
                'subscriptionStatusBreakdown' => $subscriptionStatusBreakdown,
            ]);
        }
        public function getReportData() {
            header('Content-Type: application/json');
            
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $period = $_GET['period'] ?? 30;
                
                $payment = new Payment();
                $user = new User();
                $subscription = new Subscription();
                
                // Calculate date range
                $end_date = date('Y-m-d');
                $start_date = date('Y-m-d', strtotime("-{$period} days"));
                
                // Get data for the period
                $data = [
                    'revenue_trend' => $payment->getRevenueByDateRange($start_date, $end_date),
                    'total_revenue' => $payment->totalEarned()['total_earned'],
                    'pending_revenue' => $payment->getPendingPayments()['pending_amount'],
                    'active_members' => $user->countActiveMembers()['active_member_count'],
                    'retention_rate' => $user->getRetentionRate()['rate'],
                    'member_growth' => $user->getMemberGrowthLast12Months(),
                    'revenue_by_plan' => $payment->getRevenueByPlan(),
                ];
                
                echo json_encode([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
            }
        }
        public function getFilteredReportData() {
            header('Content-Type: application/json');
            
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $payment = new Payment();
                $user = new User();
                $subscription = new Subscription();
                
                // Get filter parameters
                $dateRange = $_GET['date_range'] ?? '30';
                $startDate = $_GET['start_date'] ?? '';
                $endDate = $_GET['end_date'] ?? '';
                
                // Calculate dates
                if ($dateRange == 'custom' && !empty($startDate) && !empty($endDate)) {
                    $start = $startDate;
                    $end = $endDate;
                } else {
                    $end = date('Y-m-d');
                    $start = date('Y-m-d', strtotime("-{$dateRange} days"));
                }
                
                // Get filtered data
                $data = [
                    // Revenue data
                    'revenue_trend' => $payment->getRevenueTrend($start, $end),
                    'daily_revenue' => $payment->getDailyRevenue($start, $end),
                    'revenue_by_plan' => $payment->getRevenueByPlan($start, $end),
                    'payment_method_stats' => $payment->getPaymentMethodStats($start, $end),
                    
                    // Member data
                    'member_growth' => $user->getMemberGrowth($start, $end),
                    'members_by_plan' => $user->getMembersByPlan(),
                    'active_inactive_count' => $user->getActiveInactiveCount(),
                    'retention_rate' => $user->getRetentionRate(),
                    
                    // Payment stats
                    'payment_stats' => $payment->getPaymentStatsFiltered($start, $end),
                    'pending_payments' => $payment->getPendingPayments(),
                    
                    // Subscription data
                    'expiring_subscriptions' => $subscription->getExpiringSubscriptions(7),
                    'subscription_status_breakdown' => $subscription->getSubscriptionStatusBreakdown(),
                    
                    // Date info
                    'filter_info' => [
                        'start_date' => $start,
                        'end_date' => $end,
                        'days' => (strtotime($end) - strtotime($start)) / 86400,
                        'formatted_start' => date('M d, Y', strtotime($start)),
                        'formatted_end' => date('M d, Y', strtotime($end))
                    ]
                ];
                
                echo json_encode([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
            }
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

        public function registerMember() {
            $user = new User();

            header('Content-Type: application/json');

            if($_SERVER['REQUEST_METHOD'] == 'POST') {

                $register = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => ""];
                $registerError = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => "", "register"=>""];
                
                $register["first_name"] =  trim(htmlspecialchars($_POST['first_name']));
                $register["last_name"] = trim(htmlspecialchars($_POST['last_name']));
                $register["middle_name"] = isset($_POST['middle_name']) ? trim(htmlspecialchars($_POST['middle_name'])) : "";
                $register["email"] =  trim(htmlspecialchars($_POST['email']));
                $register["date_of_birth"] = trim(htmlspecialchars($_POST['date_of_birth']));
                $register["gender"] =  trim(htmlspecialchars($_POST['gender']));
                $register["password"] = trim(htmlspecialchars($_POST['password']));
                $register["cPassword"] = trim(htmlspecialchars($_POST['cPassword']));

                if(empty($register['first_name'])) {
                    $registerError['first_name'] = "Please provide your first name";
                }
                if(empty($register['last_name'])) {
                    $registerError['last_name'] = "Please provide your last name";
                }
                if(empty($register['email'])) {
                    $registerError['email'] = "Please  provide a valid email";
                } else if(!filter_var($register['email'], FILTER_VALIDATE_EMAIL)) {
                    $registerError['email'] = "Please provide a valid email address";
                }

                if(empty($register['date_of_birth'])) {
                    $registerError['date_of_birth'] = "Please provide you birthdate";
                } else if($register['date_of_birth'] < 12) {
                    $registerError['date_of_birth'] = "Children are not allowed in the gym";
                }
                if(!isset($register["gender"] )) {
                    $registerError['gender'] = "Please set your preferred gender";
                }
                if(empty($register['password'])) {
                    $registerError['password'] = "Password should not be empty.";
                } else if(strlen($register['password']) < 8) {
                    $registerError['password'] = "Password should not be less than 8 characters";
                } else if($register['password'] != $register['cPassword']) {
                    $registerError['password'] = "Passwords do not match";
                    $registerError['cPassword'] = "Passwords do not match";
                }

                if(empty($register['cPassword'])) {
                    $registerError['cPassword'] = "Please enter you password again.";
                }

                if(empty(array_filter($registerError))) {
                    if(!$user->findByEmail($register['email'])) {
                        $user_id = $user->addMember($register);
                        if($user_id) {
                            $subscriptionDetails = [
                                "subscription_id" => "",
                                "user_id" => "",
                                "plan_id" => "",
                                "start_date" => "",
                                "end_date" => "",
                            ];
                            $subscriptionDetails['user_id'] = $user_id['user_id'];
                            $subscriptionDetails['plan_id'] = trim(htmlspecialchars($_POST['plan_id']) ?? "");
                            $subscriptionDetails['start_date'] = date("Y-m-d");
                            $subscriptionDetails['end_date'] =  date('y-m-d', strtotime('+30 days'));

                            $subscribe = new Subscription();
                            $paymentModel = new Payment();
                            $planModel = new Plan();

                            if($subscribe->subscripePlan($subscriptionDetails)) {
                                $userCurrentPlan = $subscribe->checkUserCurrentPlan($subscriptionDetails['user_id']);
                                $userPlan = $planModel->getUserPlan($subscriptionDetails['user_id']);
                                
                                //fill up payment details
                                $paymentDetails['subscription_id'] = $userCurrentPlan['subscription_id'];
                                $paymentDetails['amount'] = $userPlan['price'];
                                $paymentDetails['payment_date'] = $userPlan['end_date'];
                                $paymentDetails['status'] = "pending";
                                if($paymentModel->openPayment($paymentDetails)) {
                                    echo json_encode([
                                        'success' => true,
                                        'message' => 'User added Successfully',
                                    ]);
                                    
                                } else {
                                    echo json_encode([
                                        'success' => false,
                                        'message' => 'Error setting up user payment',
                                    ]);
                                }

                            }   
                                                
                        } else {
                            echo json_encode([
                                'success' => false,
                                'message' => 'Error registering user, please try again',
                            ]);
                            
                        }
                    } else {
                        http_response_code(401);
                        echo json_encode([
                            'success' => false,
                            'message' => 'Error registering user: Account already exist.',
                        ]);
                    }             
                }
            } else {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'invalid request method',
                ]);
            }
        }

        public function getTrainerData() {
            header('Content-Type: application/json');
            $trainerModel = new Trainer();
            $trainer_id = $_GET['trainer_id'];
            $trainerData = $trainerModel->getTrainerById($trainer_id);
            
            if($trainerData) {
                echo json_encode([
                    'success' => true,
                    'data' => $trainerData,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' =>"an error occured.",
                ]);
            }
            
        }
    }


?>