<?php
session_start();

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

// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $verificationToken = $_GET['token'];

    // Retrieve user data by verification token
    $getUserQuery = "SELECT * FROM users WHERE verification_token = '$verificationToken'";
    $result = $conn->query($getUserQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $email = $row['email'];
        // Update user's status as verified
        $updateQuery = "UPDATE users SET verified = 1 WHERE verification_token = '$verificationToken'";
        if ($conn->query($updateQuery) === TRUE) {
            // Success: Redirect to login page with JavaScript alert
            echo '<script>alert("Email verified successfully...Now You Can Login :)."); window.location.href = "login.html";</script>';
            exit(); // Stop further execution
        } else {
            // Failure: Show error message with JavaScript alert
            echo '<script>alert("Error updating record: ' . $conn->error . '"); window.location.href = "login.html";</script>';
            exit(); // Stop further execution
        }
    } else {
        // Invalid verification token: Show error message with JavaScript alert
        echo '<script>alert("Invalid verification token."); window.location.href = "login.html";</script>';
        exit(); // Stop further execution
    }
} else {
    // Verification token not provided: Show error message with JavaScript alert
    echo '<script>alert("Verification token not provided."); window.location.href = "login.html";</script>';
    exit(); // Stop further execution
}

// Close the database connection
$conn->close();
?>
