<?php
// get_admin_emails.php

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "message"; 
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve distinct admin emails from the database
$sql = "SELECT DISTINCT user_email FROM messages"; // Select distinct emails

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $emails = array();
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['user_email'];
    }
    echo json_encode($emails); // Return the emails as JSON
} else {
    echo json_encode(array()); // Return an empty array if no emails found
}

// Close the database connection
$conn->close();
?>
