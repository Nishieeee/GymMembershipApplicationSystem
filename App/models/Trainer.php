
<?php
require_once __DIR__ . '/../config/Database.php';

class Trainer extends Database {

    public function findById($trainerId) {
        $query = "SELECT u.user_id as trainer_id, CONCAT(u.first_name, ' ', u.last_name) as name, 
                  u.email, t.specialization, t.experience_years, t.contact_no, t.status, t.join_date
                  FROM members u
                  JOIN trainers t ON u.user_id = t.user_id
                  WHERE u.user_id = :trainer_id";
        
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAssignedMembers($trainerId) {
        $query = "SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) as name, 
                  u.email, tm.assigned_date, tm.status
                  FROM members u
                  JOIN trainer_members tm ON u.user_id = tm.user_id
                  WHERE tm.trainer_id = :trainer_id AND tm.status = 'active'
                  ORDER BY tm.assigned_date DESC";
        
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStats($trainerId) {
        $stats = [];
        
        // Total members
        $query = "SELECT COUNT(*) as count FROM trainer_members WHERE trainer_id = :trainer_id AND status = 'active'";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        $stats['total_members'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Upcoming sessions
        $query = "SELECT COUNT(*) as count FROM sessions WHERE trainer_id = :trainer_id AND status = 'scheduled' AND session_date >= NOW()";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        $stats['upcoming_sessions'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Completed this month
        $query = "SELECT COUNT(*) as count FROM sessions WHERE trainer_id = :trainer_id AND status = 'completed' AND MONTH(session_date) = MONTH(NOW())";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        $stats['completed_sessions'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending requests
        $query = "SELECT COUNT(*) as count FROM trainer_requests WHERE trainer_id = :trainer_id AND status = 'pending'";
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        $stats['pending_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return $stats;
    }

    public function getPendingRequests($trainerId) {
        $query = "SELECT tr.request_id, u.user_id, CONCAT(u.first_name, ' ', u.last_name) as member_name,
                  u.email, tr.created_at as request_date
                  FROM trainer_requests tr
                  JOIN members u ON tr.user_id = u.user_id
                  WHERE tr.trainer_id = :trainer_id AND tr.status = 'pending'
                  ORDER BY tr.created_at DESC";
        
        $stmt = $this->connect()->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function handleRequest($requestId, $action) {
        $status = $action === 'accepted' ? 'accepted' : 'rejected';
        
        $this->connect()->beginTransaction();
        
        try {
            // Update request status
            $query = "UPDATE trainer_requests SET status = :status WHERE request_id = :request_id";
            $stmt = $this->connect()->prepare($query);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':request_id', $requestId);
            $stmt->execute();
            
            // If accepted, add to trainer_members
            if($action === 'accepted') {
                $query = "SELECT trainer_id, user_id FROM trainer_requests WHERE request_id = :request_id";
                $stmt = $this->connect()->prepare($query);
                $stmt->bindParam(':request_id', $requestId);
                $stmt->execute();
                $request = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $query = "INSERT INTO trainer_members (trainer_id, user_id, assigned_date, status) 
                          VALUES (:trainer_id, :user_id, NOW(), 'active')";
                $stmt = $this->connect()->prepare($query);
                $stmt->bindParam(':trainer_id', $request['trainer_id']);
                $stmt->bindParam(':user_id', $request['user_id']);
                $stmt->execute();
            }
            
            $this->connect()->commit();
            return true;
        } catch(Exception $e) {
            $this->connect()->rollBack();
            return false;
        }
    }
}