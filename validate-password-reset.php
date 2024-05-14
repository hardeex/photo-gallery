<?php
require 'reset-password.php';

// Validate password
if (!preg_match($pattern, $password)) {
    $errors['password'] = 'Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
}

// Check if passwords match
if ($password !== $confirmPassword) {
    $errors['confirmPassword'] = "Passwords do not match";
}

if (empty($errors)) {
    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    $sql = "UPDATE users SET password_hash = ?, reset_token_hash = NULL, reset_token_expires_at = NULL WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $password_hash, $user['id']);
    $stmt->execute();

    // Check if the password was updated successfully
    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Password Updated. You can now login');</script>";
        header("Location: login.php");
        exit();
    } else {
        echo "Error updating password.";
    }
} else {
    // Handle validation errors (display errors to the user or log them)
    foreach ($errors as $error) {
        echo "<p>$error</p>";
    }
}
?>