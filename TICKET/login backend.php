<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize PHPMailer
$mail = new PHPMailer;
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com'; // Your SMTP server
$mail->SMTPAuth = true;
$mail->Username = 'a6min13@gmail.com'; // SMTP username
$mail->Password = 'jmfi xkag knft weak'; // SMTP password
$mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587; // TCP port to connect to

// User registration (Sign up)
if (isset($_POST['register_first_name']) && isset($_POST['register_last_name']) && isset($_POST['register_email']) && isset($_POST['register_phone']) && isset($_POST['register_password'])) {
    $first_name = $_POST['register_first_name'];
    $last_name = $_POST['register_last_name'];
    $email = $_POST['register_email'];
    $phone = $_POST['register_phone'];
    $password = password_hash($_POST['register_password'], PASSWORD_BCRYPT);

    // Generate a verification token
    $verificationToken = md5(uniqid(rand(), true));

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo '<script>alert("Email is already registered. Please choose a different email."); window.location.href = "login.html";</script>';
        exit();
    } else {
        // Insert user data and verification token into the database
        $insertQuery = "INSERT INTO users (first_name, last_name, email, phone, password, verification_token) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$verificationToken')";
        if ($conn->query($insertQuery) === TRUE) {
            // Send verification email
            $mail->setFrom('a6min13@gmail.com', 'Admin');
            $mail->addAddress($email);
            $mail->Subject = 'Email Verification';
            $mail->Body = 'Click the following link to verify your email: <a href="http://localhost/TICKET/verify.php?token='.$verificationToken.'">Verify Email</a>';
            $mail->isHTML(true);
            if (!$mail->send()) {
                echo '<script>alert("Email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '"); window.location.href = "login.html";</script>';
                exit();
            } else {
                echo '<script>alert("You have successfully registered, to login please verify through your email"); window.location.href = "login.html";</script>';
                exit();
            }
        } else {
            echo '<script>alert("Error: ' . $conn->error . '"); window.location.href = "login.html";</script>';
            exit();
        }
    }
}

// User login
// User login
if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];

    $adminEmail = "admin@gmail.com"; // Replace with the admin email
    $adminPasswordHash = password_hash("Admin@123", PASSWORD_BCRYPT); // Replace with the admin password hash
    
    // User login
    if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
        $email = $_POST['login_email'];
        $password = $_POST['login_password'];
    
        // Check if the login credentials belong to the admin
        if ($email === $adminEmail && password_verify($password, $adminPasswordHash)) {
            // Redirect to admin.html for admin user
            header("Location: admin.html");
            exit();
        }}

   
    // Perform user authentication
    $getUserQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($getUserQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Check if the user is verified
            if ($row['verified']) {
                session_start();
                $_SESSION['email'] = $email;
                // Redirect to customer.html after successful login
                header("Location: customer.html");
                exit();
            } else {
                // User is not verified
                echo '<script>alert("Your account is not verified yet. Please check your email for verification instructions."); window.location.href = "login.html";</script>';
                exit();
            }
        } else {
            // Password is incorrect
            echo '<script>alert("Incorrect email or password."); window.location.href = "login.html";</script>';
            exit();
        }
    } else {
        // User does not exist
        echo '<script>alert("User does not exist."); window.location.href = "login.html";</script>';
        exit();
    }
}

// Close the database connection
$conn->close();
?>
