<?php
include 'include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}



// Check if the category is set and not empty
if (isset($_POST['category']) && !empty($_POST['category'])) {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Query to retrieve images for the selected category
    $sql = "SELECT * FROM images  WHERE category_name  = '$category' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Error fetching images: " . mysqli_error($conn));
    }

    // Check if there are images for the selected category
    if (mysqli_num_rows($result) > 0) {
        // Display images
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<img src="./attach/uploads/' . $row['image_filename'] . '" alt="' . $row['image_alt'] . '" class="gallery-img">';
        }
    } else {
        // No images found for the selected category
        echo '<p>No images under the selected category yet</p>';
    }
} else {
    // Category is not set or empty
    echo '<p>No category selected</p>';
}

// Close database connection
mysqli_close($conn);
?>

