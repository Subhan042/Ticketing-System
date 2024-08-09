<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $title = $_POST["title"];
    $department = $_POST["department"];
    $from = $_POST["from"];
    $to = $_POST["to"];
    $date = $_POST["date"];
    $content = $_POST["content"];

    // Handle dynamic fields based on the selected department
    $busId = $trainId = $flightId = "";

    if ($department === "Bus Ticket") {
        $busId = $_POST["busId"];
    } elseif ($department === "Train ticket") {
        $trainId = $_POST["trainId"];
    } elseif ($department === "Flight ticket") {
        $flightId = $_POST["flightId"];
    }

    // Handle file uploads
    $uploadDir = "uploads/";
    $attachments = [];

    if (is_array($_FILES["attachment"]["name"])) {
        foreach ($_FILES["attachment"]["name"] as $key => $filename) {
            $tmp_name = $_FILES["attachment"]["tmp_name"][$key];
            $target_file = $uploadDir . basename($filename);

            if (move_uploaded_file($tmp_name, $target_file)) {
                $attachments[] = $target_file;
            }
        }
    } else {
        $filename = $_FILES["attachment"]["name"];
        $tmp_name = $_FILES["attachment"]["tmp_name"];
        $target_file = $uploadDir . basename($filename);

        if (move_uploaded_file($tmp_name, $target_file)) {
            $attachments[] = $target_file;
        }
    }

    // Insert data into the database
    $attachmentList = implode(", ", $attachments);
    $sql = "INSERT INTO tickets (name, email, title, department, from_location, to_location, date_of_journey, content, attachment, bus_id, train_id, flight_id) 
            VALUES ('$name', '$email', '$title', '$department', '$from', '$to', '$date', '$content', '$attachmentList', '$busId', '$trainId', '$flightId')";

    if ($conn->query($sql) === TRUE) {
        $ticketId = $conn->insert_id; // Get the ID of the inserted ticket

        // Send email to the user
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'a6min13@gmail.com'; // Your Gmail email address
            $mail->Password   = 'jmfi xkag knft weak'; // Your Gmail password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('a6min13@gmail.com', 'Admin');
            $mail->addAddress($email); // Recipient's email address

            $mail->isHTML(true);
            $mail->Subject = 'Ticket Submission Successful';
            $mail->Body    = "Your ticket submission was successful. Ticket ID: $ticketId";

            $mail->send();
            
            // Email sent successfully, you can redirect the user to a success page
            header("Location: popup ticket.html?ticketId=$ticketId");
            exit();
        } catch (Exception $e) {
            // Email sending failed, handle the error accordingly (e.g., display an error message)
            echo "Enter the correct Email, it could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
