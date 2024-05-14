<?php

$email = $_POST["email"];
$token = bin2hex(random_bytes(32));
$token_hash = hash('sha256', $token);
$expiry = date("Y-m-d H:i:s", time() + 60 * 30); // Expires in 30 minutes

$mysqli = require __DIR__ . "../include/config.php"; // Assign the result of require to $mysqli

$sql = "UPDATE users SET reset_token_hash = ?, reset_token_expires_at = ? WHERE email = ?";
$stmt = $mysqli->prepare($sql);

// Check if prepare() succeeded
if ($stmt === false) {
    die("Error in prepare(): " . $mysqli->error);
}

// Bind parameters
if (!$stmt->bind_param("sss", $token_hash, $expiry, $email)) {
    die("Error in bind_param(): " . $stmt->error);
}

// Execute statement
if (!$stmt->execute()) {
    die("Error in execute(): " . $stmt->error);
}

// Check if any rows were affected
if ($mysqli->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("nonreply@shutterbirdng.com.com");
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->isHTML(true);
    $mail->Body = "Hi,<br><br>" .
              "You have requested to reset your password on our website. To proceed with the password reset process, please click the link below:<br><br>" .
              "Reset Password: <a href='https://shutterbirdng.com/gallery/index.php/reset-password.php?token=$token'>Reset Password</a><br><br>" .
              "If you did not initiate this password reset request, you can safely ignore this email. Your account security is important to us.<br><br>" .
              "Thank you!";


    try {
        $mail->send();
        echo "<script>alert('Password Reset link sent! Please check your inbox'); window.location.href='login.php';</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    // If the user is not found in the database
    echo "<script>alert('Password Reset link sent! Please check your inbox'); window.location.href='login.php';</script>";
}