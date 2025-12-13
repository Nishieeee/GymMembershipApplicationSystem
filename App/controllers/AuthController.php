<?php 
require_once __DIR__ . "/../Controller.php";
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../helpers/notificationHelper.php";

session_set_cookie_params(['path' => '/']);
session_start();

class AuthController extends Controller {

    public function Login() {
        $login = ["email" => "", "password" => ""];
        $loginErrors = ["email" => "", "password"=> ""];
        $loginError = "";
        
        $this->auth('login', [
            'login' => $login,
            'loginErrors' => $loginErrors,
            'loginError' => $loginError
        ]); 
    }
    
    public function Register() {
        // Add address fields to the initialization arrays
        $register = [
            "first_name" => "", "last_name" => "", "middle_name" => "", 
            "email" => "", "date_of_birth" => "", "gender" => "", 
            "password" => "", "cPassword" => "",
            "street_address" => "", "city" => "", "zip" => "" // <--- ADDED
        ];
        
        $registerError = [
            "first_name" => "", "last_name" => "", "middle_name" => "", 
            "email" => "", "date_of_birth" => "", "gender" => "", 
            "password" => "", "cPassword" => "", "register" => "",
            "street_address" => "", "city" => "", "zip" => "" // <--- ADDED
        ];
        
        $this->auth('register', [
            'register' => $register,
            'registerError' => $registerError
        ]); 
    }

    public function verifyLogin() {         
        $login = ["email" => "", "password"=> ""];
        $loginErrors = ["email" => "", "password"=> ""];
        $loginError = "";
        
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $login["email"] = isset($_POST['email']) ? trim($_POST['email']) : "";
            $login["password"] = isset($_POST['password']) ? $_POST['password'] : "";

            // Validate email
            if(empty($login['email'])) {
                $loginErrors['email'] = "Email is required.";
            } else if(!filter_var($login['email'], FILTER_VALIDATE_EMAIL)) {
                $loginErrors['email'] = "Email is invalid";
            }

            // Validate password
            if(empty($login['password'])) {
                $loginErrors['password'] = "Password is required.";
            }

            // If no validation errors, attempt login
            if(empty(array_filter($loginErrors))) {
                if($this->loginUser($login['email'], $login['password'])) {
                    if($_SESSION['role'] == 'trainer') {
                        header("Location: index.php?controller=trainer&action=trainerDashboard");
                    } else if($_SESSION['role'] == 'admin') {
                        header("Location: index.php?controller=Admin&action=dashboard");
                    } else {
                        header("Location: index.php?controller=dashboard&action=member");
                    }
                    exit();
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
        
        if($user) {
            if($password == $user['password']) {
                $_SESSION['user_id'] = $user['user_id'];             
                $_SESSION['role'] = $user['role'];
                
                if($_SESSION['role'] == 'trainer') {
                    $trainer = $userModel->getTrainerId($_SESSION['user_id']);
                    $_SESSION['trainer_id'] = $trainer['trainer_id'];
                }
                return true;
            }
        }
        return false;
    }

    public function logout() {
        session_destroy();
        header("Location: ../../public/index.php");
        exit();
    }

    public function validateUserRegistration() {
        $user = new User();

        // Re-initialize arrays to ensure keys exist
        $register = [
            "first_name" => "", "last_name" => "", "middle_name" => "", 
            "email" => "", "date_of_birth" => "", "gender" => "", 
            "password" => "", "cPassword" => "",
            "street_address" => "", "city" => "", "zip" => "" 
        ];
        
        $registerError = [
            "first_name" => "", "last_name" => "", "middle_name" => "", 
            "email" => "", "date_of_birth" => "", "gender" => "", 
            "password" => "", "cPassword" => "", "register" => "",
            "street_address" => "", "city" => "", "zip" => "" 
        ];
        
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            // --- 1. SANITIZATION ---
            $register["first_name"] = trim(htmlspecialchars($_POST['first_name']));
            $register["last_name"] = trim(htmlspecialchars($_POST['last_name']));
            $register["middle_name"] = isset($_POST['middle_name']) ? trim(htmlspecialchars($_POST['middle_name'])) : "";
            $register["email"] = trim(htmlspecialchars($_POST['email']));
            $register["date_of_birth"] = trim(htmlspecialchars($_POST['date_of_birth']));
            $register["gender"] = isset($_POST['gender']) ? trim(htmlspecialchars($_POST['gender'])) : "";
            $register["password"] = $_POST['password'];
            $register["cPassword"] = $_POST['cPassword'];

            $register["street_address"] = isset($_POST['street_address']) ? trim(htmlspecialchars($_POST['street_address'])) : "";
            $register["city"] = isset($_POST['city']) ? trim(htmlspecialchars($_POST['city'])) : "";
            $register["zip"] = isset($_POST['zip']) ? trim(htmlspecialchars($_POST['zip'])) : "";

            // --- 2. VALIDATION ---

            // Validate first name
            if(empty($register['first_name'])) {
                $registerError['first_name'] = "Please provide your first name";
            }
            
            // Validate last name
            if(empty($register['last_name'])) {
                $registerError['last_name'] = "Please provide your last name";
            }
            
            // Validate email
            if(empty($register['email'])) {
                $registerError['email'] = "Please provide a valid email";
            } else if(!filter_var($register['email'], FILTER_VALIDATE_EMAIL)) {
                $registerError['email'] = "Please provide a valid email address";
            }

            // Validate Date of Birth (Age Check)
            if(empty($register['date_of_birth'])) {
                $registerError['date_of_birth'] = "Please provide your birthdate";
            } else {
                $dob = new DateTime($register['date_of_birth']);
                $today = new DateTime();
                $age = $today->diff($dob)->y;
                
                if($age < 12) {
                    $registerError['date_of_birth'] = "You must be at least 12 years old";
                }
            }
            
            // Validate Gender
            if(empty($register["gender"])) {
                $registerError['gender'] = "Please select your gender";
            }

            // Validate Address Fields
            if(empty($register['street_address'])) {
                $registerError['street_address'] = "Street address is required";
            }
            if(empty($register['city'])) {
                $registerError['city'] = "City is required";
            }
            if(empty($register['zip'])) {
                $registerError['zip'] = "Zip code is required";
            }
            
            // Validate password
            if(empty($register['password'])) {
                $registerError['password'] = "Password should not be empty.";
            } else if(strlen($register['password']) < 8) {
                $registerError['password'] = "Password should be at least 8 characters";
            } else if($register['password'] != $register['cPassword']) {
                $registerError['password'] = "Passwords do not match";
                $registerError['cPassword'] = "Passwords do not match";
            }

            // Validate Confirm Password
            if(empty($register['cPassword'])) {
                $registerError['cPassword'] = "Please enter your password again.";
            }
            
            // Validate Agreement
            if(!isset($_POST['agreement'])) {
                $registerError['register'] = "You must agree to the terms and conditions";
            }

            // --- 3. EXECUTION ---
            // If no errors, proceed with registration
            if(empty(array_filter($registerError))) {
                if(!$user->findByEmail($register['email'])) {
                    // Pass the FULL $register array (including address) to RegisterUser
                    if($this->RegisterUser($register)) {
                        // Success! Send notification and redirect
                        NotificationHelper::newMemberRegistered(7, $register['first_name'] . ' ' . $register['last_name']);
                        header("Location: index.php?controller=auth&action=login"); 
                        exit();
                    } else {
                        // Database Insertion Failed
                        $registerError['register'] = "Error registering user. Please try again";
                        $this->auth('register', [
                            'register' => $register,
                            'registerError' => $registerError
                        ]);
                    }
                } else {
                    // Email Already Exists
                    $registerError['register'] = "Account already exists.";
                    $this->auth('register', [
                        'register' => $register,
                        'registerError' => $registerError
                    ]);
                } 
            } else {
                // Validation Failed - Return to view with errors
                $this->auth('register', [
                    'register' => $register,
                    'registerError' => $registerError
                ]);
            }
        }
    }
    
    public function RegisterUser($userData) {
        $userModel = new User();
        
        // FIXED: Hash password before storing
        // $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        
        if($userModel->addMember($userData)) {
            return true;
        } else {
            return false;
        }
    }

    public function notifyNewUser($email, $name) {
        $mail = $this->mailer();

        $mail->addAddress($email, $name);
        $mail->Subject = "Welcome to Our Community, {$name}! 🎉";
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f7fa;'>
                <div style='max-width: 600px; margin: auto; background-color: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #e1e5ea;'>

                    <h2 style='color: #3E91F7;'>Welcome aboard, {$name}! 👋</h2>

                    <p>We’re thrilled to have you here. Your account has been successfully created and you’re all set to explore our features!</p>

                    <div style='margin: 15px 0; padding: 12px; background-color: #eef5ff; border-left: 4px solid #3E91F7;'>
                        <p style='margin: 0; font-size: 15px;'>✨ Enjoy full access to your dashboard and customize your experience.</p>
                    </div>

                    <p>To get started, click the button below:</p>

                    <a href='https://your-website.com/dashboard' 
                    style='display:inline-block; margin: 15px 0; background-color: #3E91F7; 
                    color: white; padding: 12px 20px; text-decoration: none; border-radius: 6px;'>
                    Go to Dashboard
                    </a>

                    <p style='margin-top: 25px;'>If you ever need help, our support team is always ready to assist you 😊</p>

                    <hr style='margin-top: 30px;'>

                    <p style='font-size: 12px; color: #777;'>If you didn’t create this account, please ignore this email or contact support.</p>
                    <p style='font-size: 12px; color: #777;'>&copy; " . date('Y') . " Gymazing. All rights reserved.</p>
                </div>
            </div>
        ";
        $mail->AltBody = "Welcome {$name}! Your account has been created successfully. Visit your dashboard to get started.";
    }

    public function forgotPasswordNotifier($email, $name) {
        $mail = $this->mailer();
        $mail->addAddress($email, $name);
        $mail->Subject = "Forgot Password";
        $mail->isHTML(true);
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f4f6f9;'>
                <div style='max-width: 600px; margin: auto; background-color: #fff; padding: 20px; border-radius: 8px;'>
                    
                    <h2 style='color: #4CAF50;'>Password Reset Request 🔐</h2>

                    <p>Hi <strong>{$name}</strong>,</p>
                    <p>We received a request to reset your password. No worries—let’s get you back into your account.</p>

                    <div style='margin: 20px 0; padding: 15px; background-color: #fff3cd; border-left: 4px solid #ffca28;'>
                        <p style='margin: 0; font-size: 15px;'>
                            Click the button below to reset your password. This link will expire in <strong>30 minutes</strong>.
                        </p>
                    </div>

                    <a href='#' 
                        style='display:inline-block; margin-top: 15px; background-color: #4CAF50; 
                            color: white; padding: 10px 20px; text-decoration: none; 
                            border-radius: 5px; font-weight: bold;'>
                        Reset Password
                    </a>

                    <p style='margin-top: 20px; font-size: 14px; color: #555;'>
                        If you didn’t request this password reset, you can safely ignore this email.
                    </p>

                    <hr style='margin-top: 30px;'>

                    <p style='font-size: 12px; color: #777;'>Need help? Contact our support team anytime.</p>
                    <p style='font-size: 12px; color: #777;'>&copy; " . date('Y') . " Gymazing!. All rights reserved.</p>

                </div>
            </div>
        ";

        $mail->AltBody = "Hi {$name}, you requested a password reset. Click this link to reset it: <a>Link</a>";

        $mail->send();
    }
}
?>