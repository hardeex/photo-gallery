<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}
// If the user is logged in, display the dashboard content
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

    <div class="container">
        <header class="header">
            <div class="menu-toggle-container" class="menu-span-click" id="menu-span-click1">
                <span class="menu-toggle" onclick="toggleSidebar()">&#9776;</span>
            </div>

            <div class="header-item">
                <?php if(isset($_SESSION['username'])) { ?>
                <h2>Welcome <?php echo $_SESSION['username']; ?></h2>
                <?php } else { ?>
                <h2>Login</h2>
                <?php } ?>
                <?php if (isset($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
                <?php } ?>
            </div>

            <div class="header-item">
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <div class="menu-toggle-container" class="menu-span-click" id="menu-span-click2">
                <span class="menu-toggle" onclick="toggleSidebar()">&#9776;</span>
            </div>

        </header>

        <div class="main-content">
            <nav class="sidebar" id="sidebar">
                <ul>
                    <li><a href="./index.php">Go Home</a></li>
                    <li><a href="#" class="sidebar-item" data-target="widget1">Dashboard</a></li>
                    <li><a href="#" class="sidebar-item" data-target="widget2">Reports</a></li>
                    <li><a href="#" class="sidebar-item" data-target="widget3"><span
                                class="upload-icon">&#x1F4F7;</span> Upload Images</a></li>
                    <li><a href="#" class="sidebar-item" data-target="widget4">Categories </a></li>

                </ul>
            </nav>
            <div class="dashboard">
                <div class="widget" id="widget1">
                    <h2>Dashboard</h2>
                    <iframe src="./attach/chart.php" frameborder="0" width="100%" height="500px"></iframe>
                </div>
                <div class="widget" id="widget2">
                    <h2>Reports</h2>
                    <iframe src="./attach/report.php" frameborder="0" width="100%" height="500px"></iframe>
                </div>
                <div class="widget" id="widget3">
                    <h2>Upload Images</h2>
                    <iframe src="./attach/upload.php" frameborder="0" width="100%" height="500px"></iframe>
                </div>
                <div class="widget" id="widget4">
                    <h2>Photo Category</h2>
                    <iframe src="./attach/category.php" frameborder="0" width="100%" height="500px"></iframe>
                </div>

            </div>
        </div>

        <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Get all sidebar items
        const sidebarItems = document.querySelectorAll('.sidebar-item');

        // Add click event listener to each sidebar item
        sidebarItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');

                // Remove 'active' class from all sidebar items
                sidebarItems.forEach(item => {
                    item.classList.remove('active');
                });

                // Add 'active' class to the clicked sidebar item
                this.classList.add('active');

                // Show the corresponding widget and hide others
                const widgets = document.querySelectorAll('.widget');
                widgets.forEach(widget => {
                    if (widget.id === target) {
                        widget.style.display = 'block';
                    } else {
                        widget.style.display = 'none';
                    }
                });
            });
        });
        </script>

</body>

</html>