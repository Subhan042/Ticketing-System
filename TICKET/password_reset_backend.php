<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
session_start(); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if email exists in the database
$email = $_POST['email'];
$sql = "SELECT * FROM users WHERE email = '$email'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
  // Email does not exist, redirect back with error
  header("Location: forgot_password.php?error=email_not_found");
  exit();
}
$resetCode = mt_rand(100000, 999999); // Generate a random 6-digit code

// Update the database with the reset code
$updateSql = "UPDATE users SET reset_code = '$resetCode' WHERE email = '$email'";
if ($conn->query($updateSql) !== TRUE) {
  echo "Error updating record: " . $conn->error;
  exit();
}

// Send the reset code to the user's email
try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'a6min13@gmail.com'; // Your Gmail email address
    $mail->Password   = 'jmfi xkag knft weak'; // Your Gmail password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('a6min13@gmail.com', 'Admin');
    $mail->addAddress($email); // Recipient's email address

    $mail->isHTML(true);
    $mail->Subject = 'Reset password';
    $mail->Body    = "Your reset code is: $resetCode, If it is not done by you kkindly ignore it.";

    $mail->send();
    
    echo 'Reset code sent successfully';
    $_SESSION['email'] = $email;

    // Redirect the user to the reset code verification page
    header("Location: reset_code_verification.php");
    exit();
    
} catch (Exception $e) {
    $errorMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    header("Location: forgot_password.php?error=email_not_found&email_error=" . urlencode($errorMessage));
    exit();
}

?>

