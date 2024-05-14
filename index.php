<?php
session_start();
$title = "Photos";
include 'include/header.php';
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
$sql = "SELECT * FROM images ORDER BY created_at DESC LIMIT $offset,  $itemsPerPage" ;
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error retrieving images: " . mysqli_error($conn));
}

// Query to retrieve categories
$categorySql = "SELECT name FROM photo_categories";
$categoryResult = mysqli_query($conn, $categorySql);


// Retrieve description from session
$description = isset($_SESSION['image_description']) ? $_SESSION['image_description'] : '';
//$description = isset($_POST["description"]) ? mysqli_real_escape_string($conn, $_POST["description"]) : '';

// Get category and description from the form
// $category_name = mysqli_real_escape_string($conn, $_POST["category_name"]);
// $description = mysqli_real_escape_string($conn, $_POST["description"]);

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
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js">
    </script>

    <script src="script.js" defer></script>
</head>


<body>

    <div class="hero">
        <img src="img/photo-album.jpg" alt="The header image">
        <p> Photo Gallery</p>
    </div>

    <section>
        <div class="filter-gallery" style="margin-top: 0; padding-top: 0;">
            <div class="access" style="display:flex">
                <form action="search_images.php" method="get" class="search-form">
                    <input type="search" name="search" id="search" placeholder="Search for images...">
                    <button type="submit">Search</button>
                </form>


                <button id="login-btn">Login</button>
            </div>

            <h2 style="font-size: 20px; margin: 2rem">Category Lists</h2>
            <select name="category_name" class="category-select" id="category-select"
                style="width: 100%; max-width: 100%; ">
                <option value="" disabled selected>Select Category</option>
                <?php while ($row = mysqli_fetch_assoc($categoryResult)) { ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
            </select>
        </div>


        <div class="popup" id="login-popup">
            <div class="popup-content">
                <span class="close" id="close-login-popup" style="color: red;">&times;</span>
                <?php include 'login.php' ?>
            </div>
        </div>

        <br><br>

        <h2> Memories </h2>
        <!-- HTML with lazy loading placeholders -->
        <div id="image-gallery" class="gallery">
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
            <img data-src="./attach/uploads/<?php echo $row['image_filename']; ?>"
                alt="<?php echo $row['image_alt']; ?>" class="gallery-img lazy-image">
            <?php endwhile; ?>
        </div>



        <!-- Bootstrap Modal for Image View -->
        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="imageModalLabel"><?php echo $description; ?></h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="imageCarousel" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <?php 
                        $first = true;
                        mysqli_data_seek($result, 0); 
                        while ($row = mysqli_fetch_assoc($result)) : ?>
                                <div class="carousel-item <?php echo $first ? 'active' : ''; ?>">
                                    <img src="./attach/uploads/<?php echo $row['image_filename']; ?>"
                                        alt="<?php echo $row['image_alt']; ?>" class="d-block w-100">
                                </div>
                                <?php $first = false; endwhile; ?>
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


        <script>
        // JavaScript to implement lazy loading using Intersection Observer API
        document.addEventListener("DOMContentLoaded", function() {
            let lazyImages = document.querySelectorAll('.lazy-image');

            let options = {
                rootMargin: '0px',
                threshold: 0.1
            };

            let imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.classList.remove('lazy-image');
                        imageObserver.unobserve(lazyImage);
                    }
                });
            }, options);

            lazyImages.forEach(function(image) {
                imageObserver.observe(image);
            });
        });

        $(document).ready(function() {
            // Open Bootstrap Modal on Image Click
            $('.gallery-img').on('click', function() {
                $('#imageModal').modal('show');
                var index = $(this).index('.gallery-img');
                $('#imageCarousel').carousel(index); // Move carousel to clicked image
            });
        });


        $(document).ready(function() {
            $('#category-select').change(function() {
                var category = $(this).val();
                if (category !== '') {
                    $.ajax({
                        url: 'get_images.php',
                        type: 'POST',
                        data: {
                            category: category
                        },
                        success: function(response) {
                            $('#image-gallery').html(response);
                        }
                    });
                } else {
                    $('#image-gallery').load('get_all_images.php');
                }
            });
        });
        </script>


</body>

</html>