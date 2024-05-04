<?php
session_start();

$title = "Login";
include 'include/header.php';

include "include/config.php"; // Include the database configuration file


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="css/styles.css">   
</head>
<body>
    <div class="spacer" ></div>
    <div class="container">
        <h2>Create Accout</h2>
        <div id="message"></div>
        <form id="registerForm" method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>

            <button type="submit">Register</button>

            <div class="logreg">
                 <p>Have an account? <a href="login.php">Login!</a>.</p>
            </div>
           
        </form>
       
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- jQuery script -->
    <script>
$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        var username = $('#username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var confirmPassword = $('#confirmPassword').val();

        $.ajax({
            type: 'POST',
            url: 'register.php',
            data: {username: username, email: email, password: password, confirmPassword: confirmPassword},
            dataType: 'json', // Specify JSON data type
            success: function(response) {
                if (response.status === 'success') {
                    // Redirect to login page
                    window.location.href = 'login.html';
                } else {
                    if (response.message) {
                        // Display error messages
                        var errorMessage = '';
                        if (typeof response.message === 'object') {
                            $.each(response.message, function(key, value) {
                                errorMessage += '<div style="color: red;">' + value + '</div>';
                            });
                        } else {
                            errorMessage = '<div style="color: red;">' + response.message + '</div>';
                        }
                        $('#message').html(errorMessage);
                    } else {
                        $('#message').html('<p style="color: red;">An error occurred.</p>');
                    }
                }
            }
        });
    });
});
</script>


   
</body>
</html>
