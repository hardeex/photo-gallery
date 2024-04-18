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
</head>
<body>

<div class="container">
    <header style="display: flex; justify-content:space-around">
        <div>
            <h1>Dashboard</h1>
        </div>
      
        <div>
            <a href="logout.php"style="background-color: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Logout</a>
        </div>

        <div>
             <!-- <button class="sidebar-toggle" onclick="toggleSidebar()">Show/Hide Menu</button> -->
             <span style="font-size: 30px; color: white; cursor: pointer;" onclick="toggleSidebar()">&#9776;</span>
        </div>

       
    </header>
    <div class="main-content">
        <nav class="sidebar" id="sidebar">
            <ul>
                <li><a href="#" class="sidebar-item" data-target="widget1">Dashboard</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget2">Reports</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget3">Upload Images</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget4">Share on Social Media</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget5">View my Photo</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget6">All Users Photos</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget7">Manage My Photos</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget8">Settings6</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget9">Settings7</a></li>
                <li><a href="#" class="sidebar-item" data-target="widget10">Settings8</a></li>
            </ul>
        </nav>
        <div class="dashboard">
            <div class="widget" id="widget1">
                <h2>Dashboard</h2>
                <p>Display charts and graphs here....</p>
            </div>
            <div class="widget" id="widget2">
                <h2>Reports</h2>
                <p>Reports content goes here...</p>
            </div>
            <div class="widget" id="widget3">
                <h2>Upload Images</h2>
                <iframe src="./attach/upload.php" frameborder="0" width="100%" height="300px"></iframe>
            </div>
            <div class="widget" id="widget4">
                <h2>Settings2</h2>
                <p>Settings2 content goes here...</p>
            </div>
            <div class="widget" id="widget5">
                <h2>Settings3</h2>
                <p>Settings3 content goes here...</p>
            </div>
            <div class="widget" id="widget6">
                <h2>Settings4</h2>
                <p>Settings4 content goes here...</p>
            </div>
            <div class="widget" id="widget7">
                <h2>Settings5</h2>
                <p>Settings5 content goes here...</p>
            </div>
            <div class="widget" id="widget8">
                <h2>Settings6</h2>
                <p>Settings6 content goes here...</p>
            </div>
            <div class="widget" id="widget9">
                <h2>Settings7</h2>
                <p>Settings7 content goes here...</p>
            </div>
            <div class="widget" id="widget10">
                <h2>Settings8</h2>
                <p>Settings8 content goes here...</p>
            </div>
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
