<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libraries/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/SMTP.php';
require_once __DIR__ . '/../libraries/PHPMailer/Exception.php';

class SubscriptionController {

    public function notifyExpired($userEmail, $userName) {

        $mail = new PHPMailer(true);

        try {
            // SMTP Settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pagaroganjhonclein@gmail.com'; 
            $mail->Password   = 'csil fxyn phhv erhp'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('pagaroganjhonclein@gmail.com', 'Gymazing!');
            $mail->addAddress($userEmail, $userName);

            // Email Content
            $mail->isHTML(true);
            $mail->Subject = "Your Subscription Has Expired";
            $mail->Body = "
                <h3>Hello, $userName</h3>
                <p>Your subscription has expired.</p>
                <p><a href='https://your-website.com/renew'>Renew now</a> to continue enjoying our services!</p>
                <br>
                <p>Thank you!</p>
            ";
            $mail->AltBody = "Hi $userName, your subscription has expired. Please renew your plan.";

            $mail->send();
            echo "Notification sent!";
        } catch (Exception $e) {
            echo "Email failed: {$mail->ErrorInfo}";
        }
    }
}


?>