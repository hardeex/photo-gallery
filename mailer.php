<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = $_ENV['SMTP_HOST'] ?? 'smtp.example.com'; 
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = $_ENV['SMTP_PORT'] ?? 587; 
$mail->Username = $_ENV['SMTP_USERNAME'] ?? 'your-user@example.com'; 
$mail->Password = $_ENV['SMTP_PASSWORD'] ?? 'your-password'; 

$mail->isHTML(true);

return $mail;
