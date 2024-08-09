<?php
session_start();
// Initialize error message
$errorMessage = '';
$emailError = '';
// Check if there's an error message from the PHP script
if (isset($_GET['error']) && $_GET['error'] == 'email_not_found') {
    $errorMessage = '<span class="error-message">Email does not exist.</span>';
}
if (isset($_GET['email_error'])) {
    $emailError = '<span class="error-message">' . htmlspecialchars($_GET['email_error']) . '</span>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
            margin-right: 150px;
        }

        .forgot-password::before {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50%;
            height: 2px;
            background-color: #007bff;
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

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="container">
        <h2 class="forgot-password">Forgot Password</h2>
        <form id="forgot-password-form" action="password_reset_backend.php" method="post">
            <div class="input-box">
                <input type="text" name="email" id="email" placeholder="Enter your email">
                <span id="error-email" class="error-message"></span>
                <?php echo $errorMessage; ?> <!-- Display the error message -->
                <?php echo $emailError; ?> 
            </div>
            <div class="input-box button">
                <input type="submit" value="Reset Password">
            </div>
        </form>
    </div>

    <script>
        document.getElementById('email').addEventListener('input', function() {
            var errorEmail = document.getElementById('error-email');
            errorEmail.innerText = ''; // Clear any previous error message
        });
        document.getElementById('forgot-password-form').addEventListener('submit', function(event) {
            var email = document.getElementById('email').value;
            var errorEmail = document.getElementById('error-email');

            if (email.trim() === '') {
                errorEmail.innerText = '* Email is required.';
                event.preventDefault(); // Prevent form submission
            } else {
                errorEmail.innerText = ''; // Clear any previous error message
            }
        });
    </script>
</body>
</html>
