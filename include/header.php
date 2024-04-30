<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Your Logo</div>
            <div class="menu-toggle" id="menu-toggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <ul class="menu" id="menu">
                <li><a href="">Shutterbird</a></li>
                <li><a href="index.php">Gallery</a></li>
                <li><a href="">AfriwoodStreams</a></li>                
                <li><a href="">File Management</a></li>               
                <li><a href="">Email</a></li>
                <li><a href="">Urenna Portfolio</a></li>
                <li><a href="">Blog</a></li>
                <li><a href="login.html">Login/Register</a></li>
            </ul>
        </nav>
    </header>
    <script>
        document.getElementById('menu-toggle').addEventListener('click', function() {
            this.classList.toggle('active');
        });
    </script>
</body>
</html>
