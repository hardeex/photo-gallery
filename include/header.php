<!-- No changes here -->
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
            <div class="logo">
                <a href="index.php"><img src="./img/gallery.jpg" alt="Gallery Logo" style="width: 75px; height: 75px"></a>
            </div>
            <div class="menu-toggle" id="menu-toggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>
            <ul class="menu" id="menu">
                <li><a href="https://shutterbirdng.com/" target="_blank">Shutterbird</a></li>
                <li><a href="index.php">Gallery</a></li>
                <li><a href="http://afriwoodstreams.com/" target="_blank">AfriwoodStreams</a></li>                
                <li><a href="https://afriwoodstreams.com/filemanagement/" target="_blank">File Management</a></li>               
                <li><a href="https://webmail-p35.web-hosting.com/" target="_blank">Email</a></li>
                <li><a href="http://urennaamadi.com/" target="_blank">Urenna Portfolio</a></li>
                <li><a href="https://shutterbirdng.com/more.php" target="_blank">Blog</a></li>
                <li><a href="login.php">My Account</a></li>
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
