<?php
session_start();

// Check if the user is logged in
$email = $_SESSION['email'];
if ($email == false) {
    header('Location: forgot_password.php'); // Redirect to forgot password page if not logged in
    exit(); // Stop further execution
}

// Database connection parameters
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

// Handle password change request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get new password from the form
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Prepare SQL statement to update password
    $sql = "UPDATE users SET password='$hashed_password' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        // Password updated successfully
        // Destroy session and redirect to login page
        session_destroy();
        header('Location: login.html');
        exit(); // Stop further execution
    }else {
        echo "Error updating password: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
