<?php
$title = "Search Result";
include 'include/header.php';
include 'include/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="stylesheet" href="./css/styles.css">
    <!-- Add any additional CSS stylesheets here -->
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

    <script src="script.js" defer></script>
</head>
<body>

<div class="spacer"></div> <!-- Add spacing between header and content -->

<section>
    <h2>Search Results</h2>
    <div id="search-gallery" class="gallery">
        <?php
        // Establish database connection
        $conn = mysqli_connect($config['host'], $config['username'], $config['password'], $config['dbname']);

        if (!$conn) {
            die("Connection error: " . mysqli_connect_error());
        }

        // Check if the search query is set and not empty
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            
            // Query to retrieve images matching the search query
            $sql = "SELECT * FROM images WHERE description LIKE '%$search%' OR category_name LIKE '%$search%' ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (!$result) {
                die("Error fetching images: " . mysqli_error($conn));
            }

            // Check if there are images matching the search query
            if (mysqli_num_rows($result) > 0) {
                // Display images
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<img src="./attach/uploads/' . $row['image_filename'] . '" alt="' . $row['image_alt'] . '" class="gallery-img" data-toggle="modal" data-target="#imageModal">';
                }
            } else {
                // No images found for the search query
                echo '<p>No images found matching the search query</p>';
            }
        } else {
            // Search query is not set or empty
            echo '<p>Please enter a search query</p>';
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </div>
</section>

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
                <img id="previewImage" src="" alt="Image Preview" style="width: 100%;">
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Open Bootstrap Modal on Image Click
    $('.gallery-img').on('click', function(){
        var imageUrl = $(this).attr('src');
        $('#previewImage').attr('src', imageUrl);
        $('#imageModal').modal('show');
    });
});
</script>

</body>
</html>
