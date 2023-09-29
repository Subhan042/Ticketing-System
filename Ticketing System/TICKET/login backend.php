<?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// User registration (Sign up)
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if the email is already registered
    $checkEmailQuery = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "Email is already registered. Please choose a different email.";
        exit();
    } else {
        // Insert user data into the database
        $insertQuery = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";
        if ($conn->query($insertQuery) === TRUE) {
            header("Location: popup signup.html");
            exit();
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}

// User login
if (isset($_POST['email']) && isset($_POST['password'])) {
    $loginEmail = $_POST['email'];
    $loginPassword = $_POST['password'];

    $adminEmail = "admin@gmail.com"; // Replace with the admin email
    $adminPasswordHash = "admin@123"; // Replace with the admin password hash

    if ($loginEmail === $adminEmail && $loginPassword === $adminPasswordHash) {
        // Redirect to admin.html for admin user
        header("Location: admin.html");
        exit();
    }

    // Retrieve user data by email
    $getUserQuery = "SELECT * FROM users WHERE email = '$loginEmail'";
    $result = $conn->query($getUserQuery);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($loginPassword, $row['password'])) {
            $_SESSION['email'] = $loginEmail; // Set the session variable for the user's email
            header("Location: customer.html");
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }
}

// Close the database connection
$conn->close();
?>
