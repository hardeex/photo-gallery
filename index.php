<?php
include 'include/config.php';

// Establish database connection
$conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Pagination parameters
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$itemsPerPage = 20; // Number of items per page

// Calculate offset
$offset = ($page - 1) * $itemsPerPage;

// Query to retrieve images with pagination
$sql = "SELECT * FROM images LIMIT $offset, $itemsPerPage";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving images: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../auth/styles.css">
    <script src="script.js" defer></script>
</head>

<style>
        .gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .gallery img {
            max-width: 200px;
            height: auto;
        }
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            padding: 5px 10px;
            background-color: #f2f2f2;
            text-decoration: none;
            border: 1px solid #ddd;
            margin-right: 5px;
        }
        .pagination a.active {
            background-color: #4CAF50;
            color: white;
        }
    </style>
<body>


     <!-----

     TODO:
        Remember to add the functionality of sharing each image across social media
        Complete the functionality of editing each item
    -->

    <header>
        <img src="img/photo-album.jpg" alt="The header image">
        <p> Photo Gallery</p>
    </header>

    <section>
    <div class="filter-gallery">
    <div class="access" style="display:flex">
        <form action="" method="get" class="search-form">
            <input type="search" name="search" id="search" placeholder="Search...">
            <button type="submit">Search</button>
        </form>

        
                
            <button id="login-btn">Login</button>
      
    </div>
</div>

    <div class="popup" id="login-popup">
        <div class="popup-content">
            <span class="close" id="close-login-popup" style="color: red;">&times;</span>
            <?php include 'login.html' ?>
        </div>
    </div>


    <h2>Image Gallery</h2>
    <div class="gallery">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <img src="./attach/uploads/<?php echo $row['image_filename']; ?>" alt="<?php echo $row['image_alt']; ?>">
        <?php endwhile; ?>
    </div>
    <?php
    // Pagination links
    $totalImages = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM images"));
    $totalPages = ceil($totalImages / $itemsPerPage);
    ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
            <a href="?page=<?php echo $i; ?>" <?php echo $i == $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</section>

</body>
</html>
