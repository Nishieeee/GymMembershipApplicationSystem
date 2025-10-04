<?php 
session_set_cookie_params(['path' => '/']);
session_start();
    class AuthController {
        private $userModel;

        public function __construct($userModel) {
            $this->userModel = $userModel;
        }

        public function Login($email, $password) {
            $user = $this->userModel->findByEmail($email);

            if($user && $password == $user['password']) {              
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

        public function Register(array $userData) {
            if($this->userModel->addMember($userData)) {
                header("location: login.php");
            }
        }
    }
?>