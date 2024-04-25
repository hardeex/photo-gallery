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