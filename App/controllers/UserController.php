<?php 
    require_once __DIR__ . "/../config/Database.php";
    require_once __DIR__ . "/../models/User.php";
    class UserController extends User {

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

        public function validateWalkin() {
            $userModel = new User();
            header('Content-Type: application/json');
            if($_SERVER['REQUEST_METHOD'] == 'POST') {
                $walkinDetails = [
                    "first_name" => "",
                    "last_name" => "",
                    "middle_name" => "",
                    "email" => "",
                    "contact_no" => "",
                    "session_type" => "",
                    "payment_method" => "",
                    "payment_amount" => "",
                    "visit_time" => "",
                    "end_date" => "",
                ];

                $walkinDetails['first_name'] = trim(htmlspecialchars($_POST['first_name']));
                $walkinDetails['last_name'] = trim(htmlspecialchars($_POST['last_name']));
                $walkinDetails['middle_name'] = trim(htmlspecialchars($_POST['middle_name']) ?? "");
                $walkinDetails['email'] = trim(htmlspecialchars($_POST['email']) ?? "");
                $walkinDetails['contact_no'] = trim(htmlspecialchars($_POST['contact_no']));
                $walkinDetails['session_type'] = trim(htmlspecialchars($_POST['session_type']));
                $walkinDetails['payment_method'] = trim(htmlspecialchars($_POST['payment_method']));
                $walkinDetails['payment_amount'] = trim(htmlspecialchars($_POST['payment_amount']));
                $walkinDetails['visit_time'] = date("Y-m-d h:i:s");
                $walkinDetails['end_date'] = date("Y-m-d h:i:s", strtotime('+1 days'));

                if(!$walkinDetails) {
                    http_response_code(400); // Send 400 status for bad data
                    echo json_encode(['success' => false, 'message' => 'Missing required payment data from the form.']);
                    exit;
                }
                $result = $userModel->addWalkinMember($walkinDetails);

                if($result) { //user has been added successfully
                    http_response_code(200);
                    echo json_encode([
                        'success' => true,
                        'message' => 'Added Successfully',
                    ]);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error user has not been added successfully, please try again.',
                    ]);
                }
            } else {
                http_response_code(405);
                echo json_encode([
                    'success' => false,
                    'error' => 'invalid request method',
                ]);
            }
        }

        public function updateMember() {
            $user_id = $_GET['user_id'];

            $user = new User();

            $userData = [
                'first_name' => "",
                'last_name' => "",
                'middle_name' => "",
                'email' => "",
                'password' => "",
                'password' => "",
                'role' => "",
                'status' => ""
            ];

            $userData['first_name'] = trim(htmlspecialchars($_POST['first_name']));
            $userData['last_name'] = trim(htmlspecialchars($_POST['last_name']));
            $userData['middle_name'] = trim(htmlspecialchars($_POST['middle_name']));
            $userData['email'] = trim(htmlspecialchars($_POST['email']));
            $userData['password'] = trim(htmlspecialchars($_POST['password']));
            $userData['role'] = trim(htmlspecialchars($_POST['role']));
            $userData['status'] = trim(htmlspecialchars($_POST['status']));

            if($user->updateMemberViaUserId($userData, $user_id)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Update Complete!',
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error failed to update user details, Please try again.',
                ]);
            }
        }

    }



?>