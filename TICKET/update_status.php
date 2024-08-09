<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickets_db";
$port = "3306";

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    $status = $_POST['status'];
    $resolvedAt = ($status === 'closed') ? date('Y-m-d H:i:s') : null;

    // Update the "tickets" table with the new status and resolved_at
    $sql = "UPDATE tickets SET status = ?, resolved_at = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $resolvedAt, $ticketId);
    $stmt->execute();
    $stmt->close();

    // Fetch recipient email address based on ticket ID
    $getEmailSql = "SELECT email FROM tickets WHERE id = ?";
    $getEmailStmt = $conn->prepare($getEmailSql);
    $getEmailStmt->bind_param("i", $ticketId);
    $getEmailStmt->execute();
    $getEmailStmt->bind_result($email);
    $getEmailStmt->fetch();
    $getEmailStmt->close();

    // Send email to recipient
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'a6min13@gmail.com'; // Your Gmail email address
        $mail->Password   = 'jmfi xkag knft weak'; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('a6min13@gmail.com', 'Admin');
        $mail->addAddress($email); // Recipient's email address fetched from the database

        $mail->isHTML(true);
        $mail->Subject = 'Ticket Status Updated';
        $mail->Body    = "Your ticket status has been updated for Ticket ID: $ticketId";

        $mail->send();

        // Email sent successfully, you can redirect the user to a success page
        
        exit();
    } catch (Exception $e) {
        // Email sending failed, handle the error accordingly (e.g., display an error message)
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    // Handle invalid requests
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.',
    ]);
}

$conn->close();
?>
