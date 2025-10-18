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
            $plans = $plan->getAllPlans();

            $this->adminView('dashboard', [
                'members' => $members,
                'plans' => $plans,
            ]);
        } 
    }


?>