/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
*/

// Scripts

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }
    
    document.addEventListener("DOMContentLoaded", function() {
        // Get the button element
        var openVideoButton = document.getElementById("openVideoButton");
    
        // Add click event listener to the button
        openVideoButton.addEventListener("click", function(event) {
            // Prevent default behavior (e.g., following the link)
            event.preventDefault();
    
            // YouTube video ID
            var videoID = "YOUR_YOUTUBE_VIDEO_ID"; // Replace with your YouTube video ID
    
            // Generate YouTube video URL
            var videoURL = "https://www.youtube.com/embed/" + videoID;
    
            // Set the iframe src attribute to the YouTube video URL
            document.getElementById("videoIframe").src = videoURL;
    
            // Show the modal
            document.getElementById("videoModal").style.display = "block";
        });
    });
});
