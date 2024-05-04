<?php
$token = $_GET["token"];

$mysqli = require __DIR__ . "/config.php";
$sql = "SELECT * FROM users WHERE reset_token_hash = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    die("Error in prepare(): " . $mysqli->error);
}

$stmt->bind_param("s", $token);
$stmt->execute();

$result = $stmt->get_result();

if ($result === false) {
    die("Error in get_result(): " . $stmt->error);
}

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

if (strtotime($user["reset_token_expires_at"]) <= time()) {
    die("Token has expired");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            if (password !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
       
        <form action="validate-password-reset.php" method="post" onsubmit="return validatePassword();">

            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="confirmPassword" required>
            </div>

            <button type="submit">Reset Password</button>

        </form>

        <div class="logreg" id="logreg">
            <a href="login.html">Back to Login</a>
        </div>
    </div>
</body>
</html>
