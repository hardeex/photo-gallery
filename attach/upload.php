<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: ../login.php");
    exit();
}

// Database configuration
include '../include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Check if the form is submitted and files are uploaded
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
    $uploadDir = "uploads/"; // Directory where the images will be uploaded

    // Iterate through each uploaded file
    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        $uploadFile = $uploadDir . basename($_FILES["images"]["name"][$key]);
        $imageFilename = $_FILES["images"]["name"][$key];
        $imageAlt = ''; // You can set image alt if you have it in the form

        // Check if the file is an actual image
        if (!empty($tmp_name) && getimagesize($tmp_name)) {
            // Check if file already exists
            if (file_exists($uploadFile)) {
                echo "File already exists. <br>";
            } else {
                // Upload the file
                if (move_uploaded_file($tmp_name, $uploadFile)) {
                    // Insert file details into database
                    $sql = "INSERT INTO images (image_filename, image_alt) VALUES ('$imageFilename', '$imageAlt')";
                    if (mysqli_query($conn, $sql)) {
                        echo "File uploaded successfully!. <br> ";
                    } else {
                        echo "Error uploading files: <br> " . mysqli_error($conn);
                    }
                } else {
                    echo "Error uploading file. <br> ";
                }
            }
        } else {
            echo "Invalid file uploaded. <br> ";
        }
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // No files were uploaded
    echo "No files uploaded. <br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiple Image Upload</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="images[]" multiple accept="image/*" style="margin-top: 1.2rem;">
        <button type="submit" style="margin-top: 3rem">Upload Images</button>
    </form>
</body>
</html>
