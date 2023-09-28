<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "tickets_db";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $title = $_POST["title"];
    $department = $_POST["department"];
    $content = $_POST["content"];

    // Handle file uploads
    $uploadDir = "uploads/"; // Create an "uploads" directory in your project folder
$attachments = [];

// Check if it's an array or a single file
if (is_array($_FILES["attachment"]["name"])) {
    foreach ($_FILES["attachment"]["name"] as $key => $filename) {
        $tmp_name = $_FILES["attachment"]["tmp_name"][$key];
        $target_file = $uploadDir . basename($filename);

        if (move_uploaded_file($tmp_name, $target_file)) {
            $attachments[] = $target_file;
        }
    }
} else {
    // It's a single file
    $filename = $_FILES["attachment"]["name"];
    $tmp_name = $_FILES["attachment"]["tmp_name"];
    $target_file = $uploadDir . basename($filename);

    if (move_uploaded_file($tmp_name, $target_file)) {
        $attachments[] = $target_file;
    }
}

    // Insert data into the database
    $attachmentList = implode(", ", $attachments);
    $sql = "INSERT INTO tickets (name, email, title, department, content, attachment) VALUES ('$name', '$email', '$title', '$department', '$content', '$attachmentList')";

    if ($conn->query($sql) === TRUE) {
        $ticketId = $conn->insert_id; // Get the ID of the inserted ticket
        header("Location: popup ticket.html?ticketId=$ticketId"); // Redirect with ticket ID as a query parameter
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
}
// Include database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketId = $_POST["ticketId"];
    $newStatus = $_POST["status"];

    // Perform the SQL update to change the ticket status
    $updateSql = "UPDATE tickets SET status = '$newStatus' WHERE id = $ticketId";

    if ($conn->query($updateSql) === TRUE) {
        // Status updated successfully
        $response = ["success" => true, "message" => "Status updated successfully"];
        echo json_encode($response);
    } else {
        // Error updating status
        $response = ["success" => false, "message" => "Error updating status: " . $conn->error];
        echo json_encode($response);
    }
}
// Include database connection code here

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticketId = $_POST["ticketId"];
    $replyText = $_POST["replyText"];

    // Perform the SQL insert to add the reply to the database
    $insertSql = "INSERT INTO ticket_replies (ticket_id, reply_text) VALUES ($ticketId, '$replyText')";

    if ($conn->query($insertSql) === TRUE) {
        // Reply added successfully
        $response = ["success" => true, "message" => "Reply added successfully"];
        echo json_encode($response);
    } else {
        // Error adding reply
        $response = ["success" => false, "message" => "Error adding reply: " . $conn->error];
        echo json_encode($response);
    }

    // Close the database connection
}



$conn->close();
?>
