<?php
// get_admin_messages.php

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email'])) {
    $admin_email = $_GET["email"];

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

    // Retrieve admin messages from the database
    $sql = "SELECT * FROM messages WHERE user_email = '$admin_email' ORDER BY timestamp ASC";

    $result = $conn->query($sql);

    $messages = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messageType = $row['is_admin_message'] ? 'Admin' : 'User';
            $message = array(
                'type' => $messageType,
                'message' => $row['message']
            );
            $messages[] = $message;
        }
    } else {
        // No messages yet
        $messages[] = array(
            'type' => 'Info',
            'message' => 'No messages yet.'
        );
    }

    // Close the database connection
    $conn->close();

    // Return messages as JSON
    echo json_encode($messages);
} else {
    echo "Invalid request"; // Handle invalid or unauthorized requests
}
?>
