<?php
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
