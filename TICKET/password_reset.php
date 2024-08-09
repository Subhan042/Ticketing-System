<?php 
session_start();

$email = $_SESSION['email'];
if($email == false){
  header('Location: forgot_password.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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

        .form-title {
            margin-bottom: 20px;
            color: #007bff; /* Blue color for the title */
        }

        .input-box {
            position: relative; /* Make it relative for icon positioning */
            margin-bottom: 10px;
        }

        .input-box input[type="password"] {
            width: calc(100% - 30px); /* Adjusted width to accommodate the icon */
            padding: 10px;
            border: 2px solid #007bff; /* Blue border for input field */
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }

        .input-box input[type="text"] {
            width: calc(100% - 30px); /* Adjusted width to accommodate the icon */
            padding: 10px;
            border: 2px solid #007bff; /* Blue border for input field */
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box; /* Ensure padding doesn't affect width */
        }

        .input-box i {
    position: absolute;
    right: 30px;
    top: 20px;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 2;
}

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            text-align: left; /* Align error message to left */
        }

        .input-box button[type="submit"] {
            width: auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-title">Password Reset</h2>
        <form id="password-reset-form" action="new_password.php" method="POST" autocomplete="off" onsubmit="return validatePassword()">
            <div class="input-box">
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password">
                <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('new_password')"></i>
                <span class="error-message" id="error-new-password"></span>
            </div>

            <div class="input-box">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password">
                <i class="fas fa-eye-slash toggle-password" onclick="togglePassword('confirm_password')"></i>
                <span class="error-message" id="error-confirm-password"></span>
            </div>

            <div class="input-box button">
                <button type="submit">Reset Password</button>
            </div>
        </form>
    </div>

    <script>
        function togglePassword(inputId) {
            const inputField = document.getElementById(inputId);
            const icon = inputField.nextElementSibling;

            if (inputField.type === "password") {
                inputField.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                inputField.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        function validatePassword() {
            let isValid = true;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const newPasswordError = document.getElementById('error-new-password');
            const confirmPasswordError = document.getElementById('error-confirm-password');

            // Validate new password
            if (newPassword === '') {
                newPasswordError.innerText = '*New password is required.';
                isValid = false;
            } else if (newPassword.length < 8 || newPassword.length > 20) {
                newPasswordError.innerText = '*Password should be between 8 and 20 characters.';
                isValid = false;
            } else if (!/(?=.*[A-Z])/.test(newPassword)) {
                newPasswordError.innerText = '*Password must contain at least one uppercase letter.';
                isValid = false;
            } else if (!/(?=.*\W)/.test(newPassword)) {
                newPasswordError.innerText = '*Password must contain at least one symbol.';
                isValid = false;
            } else if (!/(?=.*\d)/.test(newPassword)) {
                newPasswordError.innerText = '*Password must contain at least one number.';
                isValid = false;
            } else {
                newPasswordError.innerText = ''; // Clear any previous error message
            }

            // Validate confirm password
            if (confirmPassword === '') {
                confirmPasswordError.innerText = '*Confirm password is required.';
                isValid = false;
            } else if (confirmPassword !== newPassword) {
                confirmPasswordError.innerText = '*Passwords do not match.';
                isValid = false;
            } else {
                confirmPasswordError.innerText = ''; // Clear any previous error message
            }

            return isValid;
        }
    </script>
</body>
</html>
