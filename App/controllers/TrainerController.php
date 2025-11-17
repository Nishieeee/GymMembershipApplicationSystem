
<?php
require_once __DIR__ . '/../models/Trainer.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . "/../Controller.php";

require_once __DIR__ . "/../helpers/notificationHelper.php";
class TrainerController extends Controller{
    private $trainerModel;
    private $sessionModel;

    public function __construct() {
        $this->trainerModel = new Trainer();
        $this->sessionModel = new Session();
    }

    public function trainerDashboard() {
        $trainerId = 1;

        // Fetch trainer data
        $trainerData = $this->getTrainerById($trainerId);
        $assignedMembers = $this->getAssignedMembers($trainerId);
        $upcomingSessions = $this->getUpcomingSessions($trainerId);
        $stats = $this->getTrainerStats($trainerId);

        $this->view('trainerDashboard', [
            'trainerData' => $trainerData,
            'assignedMembers' => $assignedMembers,
            'upcomingSessions' => $upcomingSessions,
            'stats' => $stats,
 
        ]);
    }

    public function getTrainerById($trainerId) {
        return $this->trainerModel->findById($trainerId);
    }

    public function getAssignedMembers($trainerId) {
        return $this->trainerModel->getAssignedMembers($trainerId);
    }

    public function getUpcomingSessions($trainerId) {
        return $this->sessionModel->getUpcomingByTrainer($trainerId);
    }

    public function getTrainerStats($trainerId) {
        return $this->trainerModel->getStats($trainerId);
    }

    public function createSession() {
        session_start();
        header('Content-Type: application/json');

        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $userId = $_POST['user_id'] ?? '';
        $trainerId = $_SESSION['trainer_id'] ?? '';
        $sessionDate = $_POST['session_date'] ?? '';
        $notes = $_POST['notes'] ?? '';

        if(empty($userId) || empty($trainerId) || empty($sessionDate)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $result = $this->sessionModel->create([
            'user_id' => $userId,
            'trainer_id' => $trainerId,
            'session_date' => $sessionDate,
            'notes' => $notes,
            'status' => 'scheduled'
        ]);
        $userModel = new User();
        $userDetails = $userModel->getMember($userId);
        if($result) {
            NotificationHelper::sessionScheduled($trainerId, $userDetails['name'] ,$sessionDate);
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => 'Session scheduled successfully']);
        } else {
            http_response_code(200);
            echo json_encode(['success' => false, 'message' => 'Failed to schedule session']);
        }
        exit;
    }

    public function updateSessionStatus() {
        header('Content-Type: application/json');
        
        $sessionId = $_POST['session_id'] ?? '';
        $status = $_POST['status'] ?? '';

        if(empty($sessionId) || empty($status)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $result = $this->sessionModel->updateStatus($sessionId, $status);

        if($result) {
            echo json_encode(['success' => true, 'message' => 'Session status updated']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        exit;
    }

    public function getPendingRequests() {
        session_start();
        header('Content-Type: application/json');
        
        $trainerId = $_GET['trainer_id'] ?? $_SESSION['trainer_id'];
        $requests = $this->trainerModel->getPendingRequests($trainerId);
        
        echo json_encode(['success' => true, 'data' => $requests]);
        exit;
    }

    public function handleRequest() {
        header('Content-Type: application/json');
        
        $requestId = $_POST['request_id'] ?? '';
        $action = $_POST['action'] ?? '';

        if(empty($requestId) || empty($action)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit;
        }

        $result = $this->trainerModel->handleRequest($requestId, $action);

        if($result) {
            echo json_encode(['success' => true, 'message' => 'Request ' . $action]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Failed to process request']);
        }
        exit;
    }
    public function deleteTrainer() {
        $trainer_id = $_POST['trainer_id'];

        $trainer = new Trainer();
        if($trainer->deleteTrainerViaId($trainer_id)) {
            echo json_encode([
                'success' => true,
                'message' => 'trainer set to inactive',
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'An error occured, Please try again.',
            ]);
        }
    }
    public function addTrainer() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        // Debug: Log received POST data
        error_log("Add Trainer POST data: " . print_r($_POST, true));

        // Validate required fields
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $middleName = trim($_POST['middle_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $contactNo = trim($_POST['contact_no'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $experienceYears = trim($_POST['experience_years'] ?? '');

        // More detailed validation messages
        $missingFields = [];
        if(empty($firstName)) $missingFields[] = 'first name';
        if(empty($lastName)) $missingFields[] = 'last name';
        if(empty($email)) $missingFields[] = 'email';
        if(empty($password)) $missingFields[] = 'password';
        if(empty($specialization)) $missingFields[] = 'specialization';

        if(!empty($missingFields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ]);
            exit;
        }

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        // Validate password length
        if(strlen($password) < 8) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
            exit;
        }

        try {
            $db = $this->trainerModel->connect();
            $db->beginTransaction();

            // Check if email already exists
            $checkQuery = "SELECT user_id FROM members WHERE email = :email";
            $stmt = $db->prepare($checkQuery);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if($stmt->fetch()) {
                $db->rollBack();
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Email already exists']);
                exit;
            }

            // Insert into members table
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $memberQuery = "INSERT INTO members (first_name, last_name, middle_name, email, password, role) 
                        VALUES (:first_name, :last_name, :middle_name, :email, :password, 'trainer')";
            $stmt = $db->prepare($memberQuery);
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':middle_name', $middleName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            $userId = $db->lastInsertId();

            // Insert into trainers table
            $trainerQuery = "INSERT INTO trainers (user_id, specialization, experience_years, contact_no, status, join_date) 
                            VALUES (:user_id, :specialization, :experience_years, :contact_no, 'active', NOW())";
            $stmt = $db->prepare($trainerQuery);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':specialization', $specialization);
            $stmt->bindParam(':experience_years', $experienceYears);
            $stmt->bindParam(':contact_no', $contactNo);
            $stmt->execute();

            $db->commit();

            echo json_encode(['success' => true, 'message' => 'Trainer added successfully']);
        } catch(Exception $e) {
            if(isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to add trainer: ' . $e->getMessage()]);
        }
        exit;
    }

    public function updateTrainer() {
        header('Content-Type: application/json');
        
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
        var_dump($_POST);
        // Debug: Log received POST data
        error_log("Update Trainer POST data: " . print_r($_POST, true));

        // Validate required fields
        $trainerId = trim($_POST['trainer_id'] ?? $_POST['edit_trainer_id'] ?? '');
        $userId = trim($_POST['user_trainer_id'] ?? $_POST['edit_trainer_user_id'] ?? '');
        $firstName = trim($_POST['trainer_first_name'] ?? $_POST['edit_trainer_first_name'] ?? '');
        $lastName = trim($_POST['trainer_last_name'] ?? $_POST['edit_trainer_last_name'] ?? '');
        $middleName = trim($_POST['trainer_middle_name'] ?? $_POST['edit_trainer_middle_name'] ?? '');
        $email = trim($_POST['trainer_email'] ?? $_POST['edit_trainer_email'] ?? '');
        $contactNo = trim($_POST['trainer_contact_no'] ?? $_POST['edit_trainer_contact_no'] ?? '');
        $specialization = trim($_POST['specialization'] ?? $_POST['edit_specialization'] ?? '');
        $experienceYears = trim($_POST['experience_years'] ?? $_POST['edit_experience_years'] ?? '');
        $status = trim($_POST['trainer_status'] ?? $_POST['edit_trainer_status'] ?? 'active');
        $password = $_POST['trainer_password'] ?? $_POST['edit_trainer_password'] ?? '';

        $missingFields = [];
        if(empty($trainerId)) $missingFields[] = 'trainer ID';
        if(empty($userId)) $missingFields[] = 'user ID';
        if(empty($firstName)) $missingFields[] = 'first name';
        if(empty($lastName)) $missingFields[] = 'last name';
        if(empty($email)) $missingFields[] = 'email';
        if(empty($specialization)) $missingFields[] = 'specialization';

        if(!empty($missingFields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false, 
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ]);
            exit;
        }

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email format']);
            exit;
        }

        // Validate password if provided
        if(!empty($password) && strlen($password) < 8) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters']);
            exit;
        }

        try {
            $db = $this->trainerModel->connect();
            $db->beginTransaction();

            // Check if email exists for another user
            $checkQuery = "SELECT user_id FROM members WHERE email = :email AND user_id != :user_id";
            $stmt = $db->prepare($checkQuery);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            if($stmt->fetch()) {
                $db->rollBack();
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Email already exists for another user']);
                exit;
            }

            // Update members table
            if(!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $memberQuery = "UPDATE members SET 
                            first_name = :first_name, 
                            last_name = :last_name, 
                            middle_name = :middle_name, 
                            email = :email,
                            password = :password
                            WHERE user_id = :user_id";
                $stmt = $db->prepare($memberQuery);
                $stmt->bindParam(':password', $hashedPassword);
            } else {
                $memberQuery = "UPDATE members SET 
                            first_name = :first_name, 
                            last_name = :last_name, 
                            middle_name = :middle_name, 
                            email = :email
                            WHERE user_id = :user_id";
                $stmt = $db->prepare($memberQuery);
            }
            
            $stmt->bindParam(':first_name', $firstName);
            $stmt->bindParam(':last_name', $lastName);
            $stmt->bindParam(':middle_name', $middleName);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();

            // Update trainers table
            $trainerQuery = "UPDATE trainers SET 
                            specialization = :specialization, 
                            experience_years = :experience_years, 
                            contact_no = :contact_no,
                            status = :status
                            WHERE trainer_id = :trainer_id";
            $stmt = $db->prepare($trainerQuery);
            $stmt->bindParam(':specialization', $specialization);
            $stmt->bindParam(':experience_years', $experienceYears);
            $stmt->bindParam(':contact_no', $contactNo);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':trainer_id', $trainerId);
            $stmt->execute();

            $db->commit();

            echo json_encode(['success' => true, 'message' => 'Trainer updated successfully']);
        } catch(Exception $e) {
            if(isset($db) && $db->inTransaction()) {
                $db->rollBack();
            }
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update trainer: ' . $e->getMessage()]);
        }
        exit;
    }
}
