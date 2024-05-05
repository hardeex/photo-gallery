<?php
session_start();

include "../include/config.php";

// Attempt to establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

// Check if the connection was successful
if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Initialize arrays to store chart data
$usernames = [];
$uploadCounts = [];

// Query to retrieve upload data grouped by user_id
$sql = "SELECT user_id, COUNT(*) AS upload_count FROM images GROUP BY user_id ORDER BY upload_count DESC LIMIT 10"; // Limit to top 10 uploaders
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error retrieving upload data: " . mysqli_error($conn));
}

// Fetch data and populate arrays
while ($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['user_id'];
    $upload_count = $row['upload_count'];
    
    // Populate arrays
    $usernames[] = $user_id; // Use user_id directly
    $uploadCounts[] = $upload_count;
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Uploaders Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="uploaderChart" width="400" height="200"></canvas>

    <script>
        // Chart data
        var ctx = document.getElementById('uploaderChart').getContext('2d');
        var uploaderChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($usernames); ?>,
                datasets: [{
                    label: 'Number of Uploads',
                    data: <?php echo json_encode($uploadCounts); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
