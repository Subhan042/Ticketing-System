<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['email'])) {
    $message = $_POST["message"];
    $user_email = $_SESSION['email'];
    $is_admin_message = "0"; // This is a user message; set it as a string

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "message";
    $port = "3306";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape the message to prevent SQL injection
    $message = mysqli_real_escape_string($conn, $message);

    // Construct the SQL query
    $sql = "INSERT INTO messages (user_email, message, is_admin_message) VALUES ('$user_email', '$message', '$is_admin_message')";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    echo "Unauthorized access"; // Handle unauthorized access
}
?>
