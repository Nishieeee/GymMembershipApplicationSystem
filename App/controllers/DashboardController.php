<?php 
    
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    require_once __DIR__ . "/../models/Plan.php";

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
            $this->view('dashboard', [
                'userInfo' => $user,
                'userPlan' => $userPlan,
            ]);
        }
        
    }
?>