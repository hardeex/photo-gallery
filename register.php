<?php
session_start();

// Include your database connection file
include "include/config.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store errors
    $errors = array();

    // Function to sanitize input
    function sanitize_input($data) {
        // Using htmlspecialchars to prevent XSS attacks
        return htmlspecialchars(trim($data));
    }

    // Retrieve and sanitize form data
    $username = isset($_POST["username"]) ? sanitize_input($_POST["username"]) : "";
    $email = isset($_POST["email"]) ? sanitize_input($_POST["email"]) : "";        
    $password = isset($_POST["password"]) ? sanitize_input($_POST["password"]) : "";
    $confirmPassword = isset($_POST["confirmPassword"]) ? sanitize_input($_POST["confirmPassword"]) : "";

    // Form validation
    if (empty($username)) {
        $errors['username'] = "Username is required";
    }
   
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['email'] = 'Enter a valid email address';
    }

    $pattern = '/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,}$/';

    // Validate password
    if (!preg_match($pattern, $password)) {
        $errors['password'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
    }
    
    // Check if passwords match
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match";
    }

    // Check if email or username already exists
    $email_check_query = "SELECT * FROM users WHERE email=?";
    $username_check_query = "SELECT * FROM users WHERE username=?";
    $stmt = mysqli_prepare($conn, $email_check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $email_count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, $username_check_query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $username_count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($email_count > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'Email already exists'));
        exit();
    }

    if ($username_count > 0) {
        echo json_encode(array('status' => 'error', 'message' => 'Username already exists'));
        exit();
    }

    // If there are errors, display them and exit
    if (!empty($errors)) {
        echo json_encode(array('status' => 'error', 'message' => $errors));
        exit();
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and execute SQL statement to insert user data into the database
    $sql = "INSERT INTO users (username, email, password)
            VALUES (?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 1) {
        // Registration successful
        echo json_encode(array('status' => 'success'));
    } else {
        // Registration failed
        echo json_encode(array('status' => 'error', 'message' => 'Registration failed'));
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close database connection
mysqli_close($conn);
?>
