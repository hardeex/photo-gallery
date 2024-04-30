<?php
include 'include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Query to retrieve all images
$sql = "SELECT * FROM images ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving images: " . mysqli_error($conn));
}

// Display all images
while ($row = mysqli_fetch_assoc($result)) {
    echo '<img src="./attach/uploads/' . $row['image_filename'] . '" alt="' . $row['image_alt'] . '" class="gallery-img">';
}

// Close database connection
mysqli_close($conn);
?>
