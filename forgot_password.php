<?php
session_start();

$title = "Login";
include 'include/header.php';

include "include/config.php"; //

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <div class="spacer"></div>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Please enter your email address to reset your password.</p>
        
        <!-- Forgot password form -->
        <form action="send-password-reset.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        
        <div class="logreg" id="logreg">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
</body>
</html>
