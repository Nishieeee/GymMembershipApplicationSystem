<?php 

    class AuthController {
        private $userModel;

        public function __construct($userModel) {
            $this->userModel = $userModel;
        }

        public function Login($email, $password) {
            $user = $this->userModel->findByEmail($email);

            if($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
            return false;
        }

        public function logout() {
            session_destroy();
            header("location: /public/index.php");
        }
    }

?>