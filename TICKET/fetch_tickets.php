<?php
// Initialize department filter
$departmentFilter = "";

// Check if a department filter is set
if (isset($_GET['department'])) {
    $departmentFilter = $_GET['department'];
}

// PHP code to fetch ticket data from a MySQL database
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

// Construct the SQL query based on the department filter
if ($departmentFilter === "all") {
    $sql = "SELECT id, name, title, department, content, attachment, status FROM tickets WHERE status != 'closed'";
} else {
    $sql = "SELECT id, name, title, department, content, attachment, status FROM tickets WHERE department = '$departmentFilter' AND status != 'closed'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<li class="ticket" data-ticket-id="' . $row["id"] . '" data-ticket-content="' . $row["content"] . '" data-ticket-attachments="' . $row["attachment"] . '" data-ticket-title="' . $row["title"] . '" data-ticket-department="' . $row["department"] . '">Ticket ' . $row["id"] . ' - ' . $row["name"] . '</li>';
    }
} else {
    echo '<li>No tickets found.</li>';
}

// Close the database connection
$conn->close();
?>
