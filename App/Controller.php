<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    class Controller {
        protected function model($model) {
            require_once __DIR__ . "/models/{$model}.php";
            
            return new $model();
        }

        protected function view($view, $data=[]) {
            extract($data);
            require __DIR__ . "/../views/{$view}.php";
        }

        protected function adminView($view, $data=[]) {
            extract($data);
            require __DIR__ . "/../views/admin/admin{$view}.php";

        }
        protected function mailer() {
            require_once __DIR__ . '/libs/phpmailer/src/PHPMailer.php';
            require_once __DIR__ . '/libs/phpmailer/src/SMTP.php';
            require_once __DIR__ . '/libs/phpmailer/src/Exception.php';

            $mail = new PHPMailer(true);

            // Default SMTP settings (adjust if needed)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'pagaroganjhonclein@gmail.com';
            $mail->Password   = 'csil fxyn phhv erhp';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('pagaroganjhonclein@gmail.com', 'Gymazing!');

            return $mail;
        }
    }

?>