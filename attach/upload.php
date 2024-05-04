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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0]) && isset($_POST["category_name"]) && !empty($_POST["category_name"])) {
    $uploadDir = "uploads/"; // Directory where the images will be uploaded

    // Get category and description from the form
    $category_name = mysqli_real_escape_string($conn, $_POST["category_name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    // Iterate through each uploaded file
    foreach ($_FILES["images"]["tmp_name"] as $key => $tmp_name) {
        $uploadFile = $uploadDir . basename($_FILES["images"]["name"][$key]);
        $imageFilename = $_FILES["images"]["name"][$key];
        $imageAlt = ''; // set the alt image from the form for enhance seo

        // Check if the file is an actual image
        if (!empty($tmp_name) && getimagesize($tmp_name)) {
            // Check if file already exists
            if (file_exists($uploadFile)) {
                echo "File already exists. <br>";
            } else {
                // Upload the file
                if (move_uploaded_file($tmp_name, $uploadFile)) {
                    // Insert file details into database along with category and description
                    $sql = "INSERT INTO images (image_filename, image_alt, category_name, description) VALUES ('$imageFilename', '$imageAlt', '$category_name', '$description')";
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
    // No files were uploaded or category was not selected
    echo "Please select a category and upload at least one file. <br>";
}

// Fetch categories from the database
$sql = "SELECT * FROM photo_categories";
$result = mysqli_query($conn, $sql);

// Check if category_name and description are set in $_POST
if (isset($_POST["category_name"]) && isset($_POST["description"])) {
    // Get category and description from the form
    $category_name = mysqli_real_escape_string($conn, $_POST["category_name"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);

    // Store description in a session variable
    $_SESSION['image_description'] = $description;
} else {
    // Handle the case when category_name or description is not set
    echo "Please select a category and add a description.";
}


if (!$result) {
    die("Error fetching categories: " . mysqli_error($conn));
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
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" class="upload-form">
        <input type="file" name="images[]" multiple accept="image/*" class="file-input"><span>You can upload mulitple images at the same time</span>
        <select name="category_name" class="category-select">
            <option value="" disabled selected>Select Category</option>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>
        <textarea name="description" placeholder="Adding a description is optional, but it boosts visibility on search engines and helps more people discover your images." class="description-textarea" cols="30" rows="10"></textarea>
        <button type="submit" class="upload-button">Upload Images</button>
    </form>


</body>
</html>
