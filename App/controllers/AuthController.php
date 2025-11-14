<?php 
require_once __DIR__ . "/../Controller.php";
require_once __DIR__ . "/../models/User.php";
session_set_cookie_params(['path' => '/']);
session_start();
    class AuthController extends Controller {


        public function Login() {
            $login = ["email" => "", "password" => ""];
            $loginErrors = ["email" => "", "password"=> ""];
            $loginError = "";
            echo "Login";
            $this->auth('login', [
                'login' => $login,
                'loginErrors' => $loginErrors,
                'loginError' => $loginError
            ]); 
        }
        public function Register() {
            $register = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => ""];
            $registerError = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => "", "register"=>""];
            $this->auth('register', [
                'register' => $register,
                'registerError' => $registerError
            ]); 
        }

        public function verifyLogin() {         
            $user = new User();
            echo "verifing user login";
            $login = ["email" => "", "password"=> ""];
            $loginErrors = ["email" => "", "password"=> ""];
            $loginError = "";
            if($_SERVER['REQUEST_METHOD'] == 'GET') {
                $login["email"] = isset($_GET['email']) ? trim(htmlspecialchars($_GET['email'])) : "";
                $login["password"] = isset($_GET['password']) ? trim(htmlspecialchars($_GET['password'])) : "";

                if(empty($login['email'])) {
                    $loginErrors['email'] = "Email is required.";
                } else if(!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
                    $loginErrors['email'] = "Email is invalid";
                }

                if(empty($login['password'])) {
                    $loginErrors['password'] = "Password is required.";
                }


                if(empty(array_filter($loginErrors))) {
                    if($this->loginUser($login['email'], $login['password'])) {
                        // header("location: index.php?controller=home&action=index");
                        echo "login success";
                    } else {
                        $loginError = "Invalid Email or Password.";
                        $this->auth('login', [
                            'login' => $login,
                            'loginErrors' => $loginErrors,
                            'loginError' => $loginError
                        ]); 
                    }
                } else {
                    $this->auth('login', [
                        'login' => $login,
                        'loginErrors' => $loginErrors,
                        'loginError' => $loginError
                    ]); 
                }
            }
        }
        public function LoginUser($email, $password) {
            $userModel = new User();
            $user = $userModel->findByEmail($email);
            if($user && $password == $user['password']) {
                echo "login Success";             
                $_SESSION['user_id'] = $user['user_id'];             
                $_SESSION['role'] = $user['role'];
                if($_SESSION['role'] == 'trainer') {
                    $trainer = $userModel->getTrainerId($_SESSION['user_id']);
                    $_SESSION['trainer_id'] = $trainer['trainer_id'];
                }
                return true;
            }
            return false;
        }

        public function logout() {
            session_destroy();
            header("location: ../../public/index.php");
        }
        public function validateUserRegistration() {
            $user = new User();

            $register = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => ""];
            $registerError = ["first_name" => "", "last_name" => "", "middle_name" => "", "email" => "", "date_of_birth" => "", "gender"=>"" , "password" => "", "cPassword" => "", "register"=>""];
            if($_SERVER['REQUEST_METHOD'] == "POST") {

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
                        if($this->RegisterUser($register)) {
                        header("location: index.php?controller=auth&action=login"); 
                        } else {
                            $registerError['register'] = "Error registering user. Please try again";
                            $this->auth('register', [
                                'register' => $register,
                                'registerError' => $registerError
                            ]);
                        }
                    } else {
                        $registerError['register'] = "Account already exist.";
                        $this->auth('register', [
                            'register' => $register,
                            'registerError' => $registerError
                        ]);
                    } 
                } else {
                    $this->auth('register', [
                        'register' => $register,
                        'registerError' => $registerError
                    ]);
                }
                
            }
        }
        public function RegisterUser($userData) {
            $userModel = new User();
            if($userModel->addMember($userData)) {
                return true;
            } else {
                return false;
            }
        }
    }
?>