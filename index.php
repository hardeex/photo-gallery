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

// Query to retrieve all images
$sql = "SELECT * FROM images LIMIT $offset, $itemsPerPage";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving images: " . mysqli_error($conn));
}

// Query to retrieve categories
$categorySql = "SELECT name FROM photo_categories";
$categoryResult = mysqli_query($conn, $categorySql);

if (!$categoryResult) {
    die("Error fetching categories: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="../auth/styles.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script src="script.js" defer></script>
</head>

<body>

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

            <h2>Category Lists</h2>
            <select name="category_name" class="category-select" id="category-select">
                <option value="" disabled selected>Select Category</option>
                <?php while ($row = mysqli_fetch_assoc($categoryResult)) { ?>
                    <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <h2>Image Gallery</h2>
    <div id="image-gallery" class="gallery">
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <img src="./attach/uploads/<?php echo $row['image_filename']; ?>" alt="<?php echo $row['image_alt']; ?>" class="gallery-img">
        <?php endwhile; ?>
    </div>

    <!-- Bootstrap Modal for Image Preview -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="imageCarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner">
                            <!-- Images will be loaded dynamically here -->
                        </div>
                        <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
$(document).ready(function(){
    $('#category-select').change(function(){
        var category = $(this).val();
        if(category !== '') {
            $.ajax({
                url: 'get_images.php',
                type: 'POST',
                data: {category: category},
                success: function(response){
                    $('#image-gallery').html(response);
                }
            });
        } else {
            $('#image-gallery').load('get_all_images.php');
        }
    });
});
</script>
