<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tickets";
$port = "3306";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected department from the request (assuming it's sent via GET or POST)
$selectedDepartment = $_GET['department']; // Adjust this based on how you are sending the department information

// Fetch tickets data based on the selected department
$sql = "SELECT id, name FROM tickets WHERE department = '$selectedDepartment'";
$result = $conn->query($sql);

$tickets = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Store ticket id and name in an array
        $ticket = [
            "id" => $row["id"],
            "name" => $row["name"]
        ];
        // Add ticket to the tickets array
        $tickets[] = $ticket;
    }
} else {
    // No tickets found
    $response = ["error" => "No tickets found"];
    echo json_encode($response);
}

// Close the database connection
$conn->close();

// Set the content type to JSON
header('Content-Type: application/json');

// Encode the tickets array as JSON and return
echo json_encode($tickets);
?>
