<?php
$title = "Search Result";
include 'include/header.php';
include 'include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Check if the search query is set and not empty
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    // Query to retrieve images matching the search query
    $sql = "SELECT * FROM images WHERE description LIKE '%$search%' OR category_name LIKE '%$search%' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching images: " . mysqli_error($conn));
    }

    // Check if there are images matching the search query
    if (mysqli_num_rows($result) > 0) {
        // Display images
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<img src="./attach/uploads/' . $row['image_filename'] . '" alt="' . $row['image_alt'] . '" class="gallery-img">';
        }
    } else {
        // No images found for the search query
        echo '<p>No images found matching the search query</p>';
    }
} else {
    // Search query is not set or empty
    echo '<p>Please enter a search query</p>';
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    
</body>
</html>
