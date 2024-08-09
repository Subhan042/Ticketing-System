<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "message";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the email parameter is set and not empty
if(isset($_GET['email']) && !empty($_GET['email'])) {
    // Sanitize the email input
    $email = $_GET['email'];

    // Prepare and execute a query to retrieve the last message and its type (admin or user) for the specified email
    $sql = "SELECT message, is_admin_message FROM messages WHERE user_email = ? ORDER BY timestamp DESC LIMIT 1";
    $statement = $conn->prepare($sql);
    $statement->bind_param("s", $email);
    $statement->execute();
    $result = $statement->get_result();

    // Fetch the result
    if($row = $result->fetch_assoc()) {
        // Return the message and its type as JSON
        echo json_encode($row);
    } else {
        // No message found for the specified email
        echo json_encode(array('error' => 'No message found for the specified email'));
    }
} else {
    // Email parameter not provided
    echo json_encode(array('error' => 'Email parameter is missing'));
}

// Close the database connection
$conn->close();
?>
