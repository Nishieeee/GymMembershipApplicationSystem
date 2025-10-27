
<?php
require_once __DIR__ . '/../models/Trainer.php';
require_once __DIR__ . '/../models/Session.php';
require_once __DIR__ . "/../Controller.php";
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

        if($result) {
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
        header('Content-Type: application/json');
        
        $trainerId = $_GET['trainer_id'] ?? $_SESSION['user_id'];
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
}
