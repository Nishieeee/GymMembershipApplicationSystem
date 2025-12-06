<?php 
    
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../models/Subscription.php";
    require_once __DIR__ . "/../models/Session.php";
    require_once __DIR__ . "/../models/Trainer.php";

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
            $sessionModel = new Session();
            $trainerModel = new Trainer();

            $user = $userModel->getMember($user_id);
            $userPlan = $planModel->getUserPlan($user_id);
            $mySessions = $sessionModel->getUserSessions($user_id);
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
                        'mySessions' => $mySessions,
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

        public function getTrainerMembers() {
            header('Content-Type: application/json');
            
            $userModel = new User();
            $db = $userModel->connect();
            
            $query = "SELECT user_id, CONCAT(first_name, ' ', last_name) as name, email 
                    FROM members 
                    WHERE role = 'trainer' 
                    ORDER BY first_name, last_name";
            
            $stmt = $db->prepare($query);
            $stmt->execute();
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'data' => $members]);
            exit;
        }

        public function bookTrainer() {
            session_start();
            header('Content-Type: application/json');

            $session = [
                "user_id" => "",
                "trainer_id" => "",
                "session_date" => "",
                "notes" => "",
                "status" => "",
                "created_at" => ""
            ];

            $sessionModel = new Session();

            


        }
        
    }
?>