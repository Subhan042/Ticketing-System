<?php
// Your database connection code here
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
    $ticketID = $_POST['ticketID'];

    // Query the database to retrieve ticket details
    $sql = "SELECT id, email, title, department, content, attachment, reply, status FROM tickets WHERE id = $ticketID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Respond with ticket details as JSON
        $response = [
            'success' => true,
            'id' => $row['id'],
            'email' => $row['email'],
            'title' => $row['title'],
            'department' => $row['department'],
            'content' => $row['content'],
            'attachment' => $row['attachment'],
            'reply' => $row['reply'],
            'status' => $row['status'],
        ];
    } else {
        // Respond with an error message as JSON
        $response = [
            'success' => false,
            'message' => 'please enter the correct ticket id.',
        ];
    }

    echo json_encode($response);
} else {
    // Handle invalid requests
    http_response_code(400);
    echo 'Invalid request.';
}

// Close the database connection
$conn->close();
?>
