$(document).ready(function(){
    // Check if users scroll to show to up button
    $(window).scroll(function() {
        if($(window).scrollTop() > 300){
            $(".top-up-button a").css({"display": "block"});
        }else{
            $(".top-up-button a").css({"display": "none"});
        }
    });

    // Show sub menu of sortby
    $("#sortby").hover(function(){
        $(".sortby-submenu").addClass("d-block");
    }, function(){
        $(".sortby-submenu").removeClass("d-block");
    });
    // Character counter for confession post
    $(".confession-textarea").keyup(function(){
        var charCount = 0;
        var textlen = charCount + $(this).val().length;
        $(".char-count").text(textlen);
    });
});