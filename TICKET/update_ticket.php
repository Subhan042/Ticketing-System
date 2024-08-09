<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickets_db";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = $_POST['ticketId'];
    $replyText = $_POST['replyText'];

    // Update the "tickets" table with the reply text and set status to "in-progress"
    $sql = "UPDATE tickets SET reply = '$replyText', status = 'in-progress' WHERE id = $ticketId";

    if ($conn->query($sql) === TRUE) {
        // Respond with a success message as JSON
        $response = [
            'success' => true,
            'message' => 'Reply added successfully.',
        ];
    } else {
        // Respond with an error message as JSON
        $response = [
            'success' => false,
            'message' => 'Error adding reply: ' . $conn->error,
        ];
    }
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
        $mail->Subject = 'Ticket Responded';
        $mail->Body    = "Your ticket has some response for Ticket ID: $ticketId";

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
    $response = [
        'success' => false,
        'message' => 'Invalid request.',
    ];
}

// Close the database connection
$conn->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
