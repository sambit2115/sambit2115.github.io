$(document).ready(function() {
    //navbar shrink
    $(window).on("scroll", function() {
        if($(this).scrollTop() > 90) {
            $(".navbar").addClass("navbar-shrink");
        } else {
            $(".navbar").removeClass("navbar-shrink");
        }
    });

    //parallax js
    function parallaxMouse(){
        if($('#parallax').length){
            var scene = document.getElementById('parallax');
            var parallax = new Parallax(scene);
        }
    }
    parallaxMouse();


    //Skills bar
    $(window).scroll(function(){
        var hT = $("#skill-bar-wrapper").offset().top,
        hH = $("#skill-bar-wrapper").outerHeight(),
        wH = $(window).height(),
        wS = $(this).scrollTop();
        if(wS > (hT+hH-1.4*wH)){
            jQuery('.skillbar-container').each(function(){
                jQuery(this).find('.skills').animate({
                    width:jQuery(this).attr('data-percent')
                }, 5000); //5 seconds
            });
        }
    });



    //navbar collapse
    $(".nav-link").on("click", function() {
        $(".navbar-collapse").collapse("hide");
    });


    //scroll
    $.scrollIt({
        topOffset: -50
    });


    // Form submission handling
    $("#contactForm").on("submit", function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = $(this).serialize(); // Serialize the form data
        console.log("Form Data: ", formData); // Log the form data for debugging

        $.ajax({
            url: "send_mail.php",
            type: "POST",
            data: formData,
            success: function(response) {
                console.log("Response: ", response); // Log the response for debugging
                var jsonResponse = JSON.parse(response);
                if (jsonResponse.status === "success") {
                    alert("Message sent successfully!");
                    window.location.href = "index.html"; // Redirect to index.html
                } else {
                    alert("Error: " + jsonResponse.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: ", error); // Log any errors for debugging
                console.log("XHR: ", xhr); // Log the XHR object for debugging
                console.log("Status: ", status); // Log the status for debugging
                alert("An error occurred while sending the message.");
            }
        });
    });
})