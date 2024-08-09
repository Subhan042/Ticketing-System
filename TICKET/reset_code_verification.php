<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";
$port = "3306";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the code entered by the user
    $enteredCode = $_POST['code'];

    // Retrieve the email from session
    $email = $_SESSION['email'];

    // Query the database to fetch the reset code associated with the email
    $sql = "SELECT reset_code FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedCode = $row['reset_code'];

        // Compare the entered code with the reset code stored in the database
        if ($enteredCode == $storedCode) {
            // If the codes match, redirect the user to the password reset page
            header("Location: password_reset.php");
            exit();
        } else {
            // If the codes don't match, display an error message
            $errorMessage = "Invalid code. Please try again.";
        }
    } else {
        // If no matching record found for the email, display an error message
        $errorMessage = "Email not found in the database.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Code Verification</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('../TICKET/Images/login.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .forgot-password {
            position: relative;
            margin-bottom: 30px;
            text-align: center; /* Center align the text */
            color: #007bff; /* Blue color for the heading */
            text-decoration: underline; /* Underline the heading */
            text-decoration-color: #007bff; /* Set the underline color */
            text-decoration-thickness: 3px; /* Set the thickness of the underline */
            text-underline-offset: 3px; 
        }

        

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .input-box input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 2px solid #007bff; /* Blue border for input field */
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }

        .input-box.button {
            margin-top: 20px; /* Adjust the margin-top to position the button down */
        }

        .input-box.button input[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        /* Style for the paragraph */
        .container p {
            margin-bottom: 20px; /* Add some bottom margin */
            font-size: 14px; /* Adjust the font size */
            color: #333; /* Change the text color */
        }
    </style>
</head>
<body>
<div class="container">
        <h2 class="forgot-password">Reset Code Verification</h2>
        <p>To reset your password, please enter the verification code sent to your email address. If you haven't received the code, please check your spam folder or request another code.</p>
        <?php
        if (isset($errorMessage)) {
            echo '<div class="error-message">' . $errorMessage . '</div>';
        }
        ?>
        <form id="verification-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-box">
                <input type="text" name="code" placeholder="Enter the code">
                <span class="error-message" id="code-error-message"></span> <!-- Changed ID here -->
            </div>
            <div class="input-box button">
                <input type="submit" value="Verify Code">
            </div>
        </form>
    </div>
    <script>
         document.getElementById('verification-form').addEventListener('submit', function(event) {
            var code = document.getElementsByName('code')[0].value; // Changed to getElementsByName
            var errorMessage = document.getElementById('code-error-message'); // Changed ID here

            if (code.trim() === '') {
                errorMessage.innerText = '* Verification code is required.';
                event.preventDefault(); // Prevent form submission
            } else {
                errorMessage.innerText = ''; // Clear any previous error message
            }
        });
    </script>
</body>
</html>