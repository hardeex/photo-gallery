document.addEventListener('DOMContentLoaded', function () {
    // Get the popup elements and buttons
    var loginPopup = document.getElementById('login-popup');
    var loginBtn = document.getElementById('login-btn');
    var closeLoginPopup = document.getElementById('close-login-popup');
    var uploadPopup = document.getElementById('upload-popup');
    var addToGalleryBtn = document.getElementById('add-to-gallery');
    var closeUploadPopup = document.getElementById('close-popup');

    console.log('addToGalleryBtn:', addToGalleryBtn);

    // Show login popup when login button is clicked
    loginBtn.addEventListener('click', function () {
        loginPopup.style.display = 'block';
    });

    // Hide login popup when close button is clicked
    closeLoginPopup.addEventListener('click', function () {
        loginPopup.style.display = 'none';
    });

    // Show upload popup when add to gallery button is clicked
    // addToGalleryBtn.addEventListener('click', function () {
    //     uploadPopup.style.display = 'block';
    // });

    // Hide upload popup when close button is clicked
    closeUploadPopup.addEventListener('click', function () {
        uploadPopup.style.display = 'none';
    });
});


