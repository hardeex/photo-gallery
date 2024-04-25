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

// Check if the form to create category is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["category"]) && !empty(trim($_POST["category"]))) {
    $category = trim($_POST["category"]);

    // Check if the category already exists
    $checkSql = "SELECT * FROM photo_categories WHERE name = '$category'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) > 0) {
        $categoryError = "Category already exists!";
    } else {
        // Insert category into database
        $insertSql = "INSERT INTO photo_categories (name) VALUES ('$category')";
        if (mysqli_query($conn, $insertSql)) {
            $categorySuccess = "Category created successfully!";
        } else {
            $categoryError = "Error creating category: " . mysqli_error($conn);
        }
    }
}


// Fetch categories from the database
$sql = "SELECT * FROM photo_categories";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error fetching categories: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Categories</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>


<body>
    <h2>Create Category</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="category" id="category" placeholder="Category Name" required style="width: 60%;">
        <button type="submit" style="margin-top: 3rem">Create Category</button>
    </form>
    <?php if (isset($categoryError)) { ?>
        <div class="error"><?php echo $categoryError; ?></div>
    <?php } ?>
    <?php if (isset($categorySuccess)) { ?>
        <div class="success"><?php echo $categorySuccess; ?></div>
    <?php } ?>


    <h2>Category Lists</h2>
    <select name="category_name" class="category-select">
            <option value="" disabled selected>Select Category</option>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select>
   


   
    
</body>
</html>
