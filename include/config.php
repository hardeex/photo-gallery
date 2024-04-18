<?php
// Database configuration
$config = [
    'host' => 'localhost',
    'dbname' => 'photo_gallery',
    'username' => 'root',
    'password' => ''
];

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

return $conn;


?>
