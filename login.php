<?php
session_start();

$title = "Login";
include 'include/header.php';

include "include/config.php"; // Include the database configuration file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Establish database connection
    $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username_or_email = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username_or_email, $username_or_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username']; // Set username in session
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username/email or password";
        }
    } else {
        $error = "User not found";
    }

    mysqli_close($conn); // Close database connection
}
?>


<!--- Hndling errors from the login.html page-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">

  </head>
<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (isset($error)) { ?>
            <div class="error"><?php echo $error; ?></div>
        <?php } ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Email or Username:</label>
                <input type="text" id="username" name="username" placeholder="Email or Username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="logreg" id="logreg" style="margin-bottom: 15px;">
                <a href="register.html">Sign up?</a>
                <a href="forgot_password.php">Forgot Password</a>
            </div>

            <a href="./index.php" class="go-home">Go Home</a> <br><br>
            <button type="submit">Login</button>
        </form>
       
    </div>
</body>
</html>
