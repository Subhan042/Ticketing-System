<?php
// logout.php

// Start the session to access session variables
session_start();

// Check if the 'logout' parameter is set in the URL
if (isset($_GET['logout'])) {
    // Destroy the session to log the user out
    session_destroy();
    // Redirect to the login page
    header("Location: ../TICKET/login.html");
    exit();
} else {
    // Redirect to an error page or handle the situation accordingly
    header("Location:../TICKET/login.html");
    exit();
}
?>
