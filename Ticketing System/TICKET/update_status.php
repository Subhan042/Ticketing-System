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
    $status = $_POST['status'];

    // Update the "tickets" table with the new status
    $sql = "UPDATE tickets SET status = '$status' WHERE id = $ticketId";

    if ($conn->query($sql) === TRUE) {
        // Respond with a success message as JSON
        $response = [
            'success' => true,
            'message' => 'Status updated successfully.',
        ];
    } else {
        // Respond with an error message as JSON
        $response = [
            'success' => false,
            'message' => 'Error updating status: ' . $conn->error,
        ];
    }

    echo json_encode($response);
} else {
    // Handle invalid requests
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.',
    ]);
}

// Close the database connection
$conn->close();
?>
