<?php 
    require_once __DIR__ . "/../Controller.php";
    require_once __DIR__ . "/../models/Plan.php";
    require_once __DIR__ . "/../config/Database.php";

    class PlanController extends Controller {

        public function viewPlans() {
            $planModel = new Plan();
            $plans = $planModel->getAllActivePlans();
            
            $this->view('plans', [
                'plans' => $plans,
            ]);
        }
    }


?>