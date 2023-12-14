<?php
// send_admin_reply.php

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $admin_email = $_POST['email'];
    $reply = $_POST["reply"];

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "message"; // Replace with your actual database name
    $port = "3306";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Escape the reply to prevent SQL injection
    $reply = mysqli_real_escape_string($conn, $reply);

    // Construct the SQL query to insert the reply
    $sql = "INSERT INTO messages (user_email, message, is_admin_message) VALUES ('$admin_email', '$reply', 1)";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Set the Content-Type header to indicate a JSON response
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Reply sent successfully'));
    } else {
        // Set the Content-Type header to indicate a JSON response
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Error sending reply'));
    }

    // Close the database connection
    $conn->close();
} else {
    // Set the Content-Type header to indicate a JSON response
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Invalid request'));
}
?>
