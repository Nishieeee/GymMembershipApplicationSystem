<?php 
require_once __DIR__ . "/models/NotificationModel.php";
require_once __DIR__ . "/../config/Database.php";
class NotificationHelper {
    private static $notificationModel;
    
    private static function getModel() {
        if(self::$notificationModel === null) {
            self::$notificationModel = new NotificationModel();
        }
        return self::$notificationModel;
    }
    
    // ===== MEMBERSHIP NOTIFICATIONS =====
    
    public static function membershipExpiring($userId, $daysLeft) {
        return self::getModel()->create(
            $userId,
            'Membership Expiring Soon',
            "Your membership will expire in $daysLeft days. Please renew to continue enjoying our services.",
            'warning',
            'membership',
            'index.php?controller=member&action=renewMembership'
        );
    }
    
    public static function membershipExpired($userId) {
        return self::getModel()->create(
            $userId,
            'Membership Expired',
            'Your membership has expired. Please renew to continue using the gym.',
            'error',
            'membership',
            'index.php?controller=member&action=renewMembership'
        );
    }
    
    public static function membershipRenewed($userId, $newExpiryDate) {
        return self::getModel()->create(
            $userId,
            'Membership Renewed',
            "Your membership has been successfully renewed until $newExpiryDate.",
            'success',
            'membership'
        );
    }
    
    // ===== PAYMENT NOTIFICATIONS =====
    
    public static function paymentReceived($userId, $amount) {
        return self::getModel()->create(
            $userId,
            'Payment Received',
            "We have received your payment of ₱$amount. Thank you!",
            'success',
            'payment',
            'index.php?controller=member&action=paymentHistory'
        );
    }
    
    public static function paymentDue($userId, $amount, $dueDate) {
        return self::getModel()->create(
            $userId,
            'Payment Due',
            "You have a pending payment of ₱$amount due on $dueDate.",
            'warning',
            'payment',
            'index.php?controller=member&action=makePayment'
        );
    }
    
    // ===== BOOKING NOTIFICATIONS =====
    
    public static function bookingConfirmed($userId, $sessionType, $date, $time) {
        return self::getModel()->create(
            $userId,
            'Booking Confirmed',
            "Your $sessionType session on $date at $time has been confirmed.",
            'success',
            'booking',
            'index.php?controller=member&action=myBookings'
        );
    }
    
    public static function bookingCancelled($userId, $sessionType, $date) {
        return self::getModel()->create(
            $userId,
            'Booking Cancelled',
            "Your $sessionType session on $date has been cancelled.",
            'warning',
            'booking',
            'index.php?controller=member&action=myBookings'
        );
    }
    
    public static function sessionReminder($userId, $sessionType, $time) {
        return self::getModel()->create(
            $userId,
            'Session Reminder',
            "Reminder: You have a $sessionType session at $time today.",
            'info',
            'schedule'
        );
    }
    
    // ===== TRAINER NOTIFICATIONS =====
    
    public static function newClientAssigned($trainerId, $clientName) {
        return self::getModel()->create(
            $trainerId,
            'New Client Assigned',
            "You have been assigned a new client: $clientName.",
            'info',
            'trainer',
            'index.php?controller=trainer&action=clients'
        );
    }
    
    public static function sessionScheduled($trainerId, $clientName, $date, $time) {
        return self::getModel()->create(
            $trainerId,
            'New Session Scheduled',
            "A new session with $clientName has been scheduled for $date at $time.",
            'info',
            'schedule',
            'index.php?controller=trainer&action=schedule'
        );
    }
    
    // ===== ADMIN NOTIFICATIONS =====
    
    public static function newMemberRegistered($adminId, $memberName) {
        return self::getModel()->create(
            $adminId,
            'New Member Registered',
            "A new member has registered: $memberName.",
            'info',
            'general',
            'index.php?controller=admin&action=members'
        );
    }
    
    public static function paymentReceived_Admin($adminId, $memberName, $amount) {
        return self::getModel()->create(
            $adminId,
            'Payment Received',
            "$memberName has made a payment of ₱$amount.",
            'success',
            'payment',
            'index.php?controller=admin&action=payments'
        );
    }
    
    public static function notifyAllAdmins($title, $message, $type = 'info', $link = null) {
        return self::getModel()->notifyByRole('admin', $title, $message, $type, 'general', $link);
    }
    
    // ===== GENERAL NOTIFICATIONS =====
    
    public static function systemAnnouncement($title, $message) {
        // Notify all active users
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->query("SELECT user_id FROM users WHERE is_active = 1");
        $users = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        foreach($users as $userId) {
            self::getModel()->create($userId, $title, $message, 'info', 'general');
        }
        
        return true;
    } 
}
?>