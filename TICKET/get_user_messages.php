<?php
// get_user_messages.php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_SESSION['email'])) {
    $user_email = $_SESSION["email"];

    // Retrieve messages for the user from the database (replace with your database connection code)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "message";
    $port = "3306";

    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM messages WHERE user_email = '$user_email' ORDER BY timestamp ASC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messageType = $row['is_admin_message'] ? 'Admin' : 'User';
            echo "<p><strong>$messageType:</strong> {$row['message']}</p>";
        }
    } else {
        echo "<p>No messages yet.</p>";
    }

    $conn->close();
} else {
    echo "Unauthorized access"; // Handle unauthorized access
}
?>
