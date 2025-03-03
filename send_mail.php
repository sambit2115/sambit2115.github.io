<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'config.php'; // Secure credentials file

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

// Sanitize and validate input
$name = htmlspecialchars($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars($_POST['phone'] ?? '');
$subject = htmlspecialchars($_POST['subject'] ?? '');
$message = htmlspecialchars($_POST['message'] ?? '');

if (!$email || empty($subject) || empty($message)) {
    echo json_encode(["status" => "error", "message" => "Valid email, subject, and message are required."]);
    exit;
}

$mail = new PHPMailer(true);
try {
    // Mail server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Set sender's email address
    $mail->setFrom(SMTP_USER, "Website Contact Form"); // Your email (cannot be changed due to Gmail policies)
    $mail->addReplyTo($email, $name); // The user’s actual email (allows replying directly to them)
    $mail->addAddress(RECIPIENT_EMAIL); // Your receiving email

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = "
        <h2>Contact Request</h2>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";
    $mail->AltBody = strip_tags($message);

    if ($mail->send()) {
        echo json_encode(["status" => "success", "message" => "Message sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Message could not be sent."]);
    }
} catch (Exception $e) {
    error_log("Email error: " . $mail->ErrorInfo); // Log error instead of exposing it
    echo json_encode(["status" => "error", "message" => "An error occurred while sending the email."]);
}
?>
