<?php 

    include_once "../App/models/Plan.php";

    class PlanController extends Plan {
        public function GetAllPlan() {
            if(!isset($_SESSION['user_id'])) {
                return null;
            } else {
                return $this->getAllPlans();
            }
        }
        public function AddNewSubscription($formData) {
            if($this->addSubscription($formData)) {
                header("location: dashboard.php");
            } else {
                header("location: attendance.php");
            }
        }
    }

?>