<?php 
    require_once "../../config/Database.php";

    class UserController extends User {

        public function __constructor($userModel, $planModel) {
            $this->userModel = $userModel;
            $this->planModel = $planModel;
        }
        public function getUserDetails($user_id) {
            $sql = "SELECT CONCAT(m.first_name, ' ', m.last_name) as name, m.first_name, m.email, m.role, m.created_at, p.plan_name, s.end_date, s.status FROM members m 
            JOIN subscriptions s ON s.user_id = m.user_id
            LEFT JOIN membership_plans p ON p.plan_id = s.plan_id 
            WHERE m.user_id = :user_id";

            $query = $this->connect()->prepare($sql);
            $query->bindParam(":user_id", $user_id);

            if($query->execute()) {
                return $query->fetch();
            } else {
                return null;
            }
        }

    }



?>