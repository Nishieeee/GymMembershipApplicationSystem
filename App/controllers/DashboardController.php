<?php 
    
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../models/Subscription.php";

    class DashboardController extends Controller {
        protected $db;
        public function __construct() {
            $database = new Database();
            $this->db = $database->connect();
        }

        public function member() {
            session_start();
            $user_id = $_SESSION['user_id'];

            $userModel = new User($this->db);
            $planModel = new Plan();

            $user = $userModel->getMember($user_id);
            $userPlan = $planModel->getUserPlan($user_id);
            //expire user plan
            if(isset($userPlan) && $userPlan['end_date'] <= date("Y-m-d")) {
                echo $userPlan['end_date'];
                header("location: index.php?controller=Subscribe&action=expirePlan");
            }
            if(isset($userPlan['status'])) {
                if($userPlan['status'] == "active") {
                    $this->view('dashboard', [
                        'userInfo' => $user,
                        'userPlan' => $userPlan,
                    ]);
                } else {
                    if($userPlan['status'] == "cancelled") {
                        $userPlan["status"] = "cancelled";
                        $this->view('dashboard', [
                            'userInfo' => $user,
                            'userPlan' => $userPlan,
                        ]);
                    } else if($userPlan['status'] == "expire") {
                        $userPlan["status"]  = "expired";
                        $this->view('dashboard', [
                            'userInfo' => $user,
                            'userPlan' => $userPlan,
                        ]);

                    }
                }
            } else {
                $this->view('dashboard', [
                    'userInfo' => $user,
                    'userPlan' => $userPlan,
                ]);
            }
        }

        public function cancelSubscription() {
            $this->view('cancelSubscription',[]);
        } 
        
    }
?>