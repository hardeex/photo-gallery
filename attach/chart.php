<?php
session_start();

// Database configuration
include '../include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Query to retrieve total uploads per day
$sqlTotalUploads = "SELECT DATE(created_at) AS created_date, COUNT(*) AS total_uploads
                    FROM images
                    GROUP BY created_date
                    ORDER BY created_date";

$resultTotalUploads = mysqli_query($conn, $sqlTotalUploads);

if (!$resultTotalUploads) {
    die("Error retrieving total upload data: " . mysqli_error($conn));
}

// Initialize arrays to store data for Chart.js
$dates = [];
$totalUploads = [];

// Process total upload query results
while ($row = mysqli_fetch_assoc($resultTotalUploads)) {
    $date = $row['created_date'];
    $total = $row['total_uploads'];

    // Store date and total uploads
    $dates[] = $date;
    $totalUploads[] = $total;
}

// Query to retrieve top 20 uploader usernames
$sqlTopUploaders = "SELECT id, COUNT(*) AS total_uploads
                    FROM images
                    GROUP BY id
                    ORDER BY total_uploads DESC
                    LIMIT 20";

$resultTopUploaders = mysqli_query($conn, $sqlTopUploaders);

if (!$resultTopUploaders) {
    die("Error retrieving top uploader data: " . mysqli_error($conn));
}

// Initialize array to store top uploader usernames
$topUploaders = [];

// Process top uploader query results
while ($row = mysqli_fetch_assoc($resultTopUploaders)) {
    $userId = $row['id'];
    $totalUploads = $row['total_uploads'];

    // Query username for user ID
    $sqlUsername = "SELECT username FROM users WHERE id = $userId";
    $resultUsername = mysqli_query($conn, $sqlUsername);

    // Check if the query was successful
    if ($resultUsername) {
        $usernameRow = mysqli_fetch_assoc($resultUsername);
        // Check if a username was found
        if ($usernameRow && isset($usernameRow['username'])) {
            $username = $usernameRow['username'];
            // Store username and total uploads
            $topUploaders[] = ['username' => $username, 'total_uploads' => $totalUploads];
        } else {
            // Handle case where username is not found
            $topUploaders[] = ['username' => 'Unknown', 'total_uploads' => $totalUploads];
        }
    } else {
        // Handle query error
        die("Error retrieving username: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <canvas id="uploadChart" width="800" height="400"></canvas>

<script>
    // Create Chart.js instance
    var ctx = document.getElementById('uploadChart').getContext('2d');
    var uploadChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($dates); ?>,
            datasets: [
                {
                    label: 'Total Uploads',
                    data: <?php echo json_encode($totalUploads); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false
                }
            ]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'day'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

<!-- Display top uploader usernames -->
<h2>Top 20 Uploaders</h2>
<ul>
    <?php if (!empty($topUploaders)) : ?>
        <?php foreach ($topUploaders as $uploader) : ?>
            <?php if (!empty($uploader) && isset($uploader['username'])) : ?>
                <li><?php echo $uploader['username']; ?> - <?php echo $uploader['total_uploads']; ?> uploads</li>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <li>No top uploaders found.</li>
    <?php endif; ?>
</ul>

</body>
</html>
