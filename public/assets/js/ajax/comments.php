<script type="text/javascript">
    $(document).ready(function() {
        $("#comment").on("click", function() { // On button clicked that has id comment to focus on textarea to comment
            $("#comment_text").focus();
        });

        var confess_id = $("#post_comment").attr("data-confess-id"); // Getting confession id
        
        recive_comments(); // Call recive_comments function

        $("#post_comment").on("click", function(e) { // When user click to post comment
            e.preventDefault();
            $(this).attr("disabled", true); // Button will be disabled

            var username = $("#username").val(); // Getting username from the user
            var comment_text = $("#comment_text").val(); // Getting comment that user send
            var reply_to = $(this).attr("data-reply-to"); // If user reply to somebody comment get id
            var data = ''; // Defining an empty string for ajax 

            $(".comment-data").css({ // Selecting and displaying comment
                "display": "block"
            });

            setTimeout(function() { // After 5 seconds button POST will be avaliable again
                $("#post_comment").attr("disabled", false);
            }, 5000);

            $.ajax({
                url: "<?= site_url("insert_comment") ?>",
                type: "POST",
                data: {
                    username: username,
                    comment_text: comment_text,
                    confess_id: confess_id,
                    reply_to: reply_to
                },
                success: function(response) {
                    var res = JSON.parse(response);

                    if (res.error) { // If there was some error to show it
                        var errors = res.error;
                        for (var i in errors) {
                            var err = errors[i];
                            data += "<p class='text-center text-danger'>" + err + "</pre>";
                            $(".comment-data").html(data).delay(8200).fadeOut(300);
                        }
                    }

                    if (res.success) { // If there was a success of posting comment or reply to comment it will show success message
                        $("#post_comment").attr("data-reply-to", " ");
                        data += "<p class='text-center text-success'>Comment Successfully Posted!</p>";
                        $(".comment-data").html(data).delay(4200).fadeOut(300);
                        data += "";
                        $("#username").val('');
                        $("#comment_text").val('');
                        recive_comments();
                    }
                }
            });
        });
        // Recive comments functions
        function recive_comments() {
            $.ajax({
                url: "<?= site_url("recive_comments") ?>",
                type: "POST",
                data: {
                    confess_id: confess_id
                },
                success: function(response) {
                    var res = JSON.parse(response);
                    // To print all comments inside all-comments div
                    $(".all-comments").html(res.comments);

                    var is_value_clicked = 0;   // // Created a variable that's count how many time user click on like or dislike
                    // On like or dislike button click to do ajax
                    $(".comment-action").click(function(e){
                        e.preventDefault();
                        is_value_clicked += 1; // Incrase value by one
                        // var confess_id = $(this).attr("data-confess-id"); // Getting confess id value
                        var data_value = $(this).attr("data-value").toLowerCase(); // What was clicked like or dislike
                        var comment_id = $(this).attr("data-comment-id"); // Getting comment id if user liked/dislike some comment
                        var reply_comment_id = $(this).attr("data-reply-comment-id"); // Getting reply comment id if user liked/dislike some reply comment
                        $.ajax({
                            url: "<?= site_url("comments_actions") ?>",
                            type: "POST",
                            data:{
                                is_clicked: is_value_clicked,
                                confess_id: confess_id,
                                clicked_value: data_value,
                                comment_id: comment_id,
                                reply_comment_id: reply_comment_id
                            },
                            success: function(response) {
                                var res = JSON.parse(response);
                                // Adding clicking effect on like/dislike
                                if(res.clicked_value == "approve" && res.comment_id != 0){
                                    $(".comment-approve-"+res.comment_id).addClass("comment-action-active");
                                    $(".comment-disapprove-"+res.comment_id).removeClass("comment-action-active");
                                }else{
                                    $(".comment-approve-"+res.comment_id).removeClass("comment-action-active");
                                    $(".comment-disapprove-"+res.comment_id).addClass("comment-action-active");
                                }       
                                
                                if(res.clicked_value == "approve" && res.reply_comment_id != 0){
                                    $(".reply-approve-"+res.reply_comment_id).addClass("comment-action-active");
                                    $(".reply-disapprove-"+res.reply_comment_id).removeClass("comment-action-active");
                                }else{
                                    $(".reply-approve-"+res.reply_comment_id).removeClass("comment-action-active");
                                    $(".reply-disapprove-"+res.reply_comment_id).addClass("comment-action-active");
                                }
                            },
                            error: function(){
                                alert("Sorry, there was some error, try again later..");
                            }
                        });
                    });

                    $(".reply").on("click", function(e) { // If user click to reply to some comment
                        e.preventDefault();
                        $("#comment_text").focus();
                        $("#username").val('');
                        $("#comment_text").val('');
                        $("#post_comment").attr("data-reply-to", $(this).attr("data-reply"));
                    });
                },
                error: function(){
                    alert("Sorry, there was some error, try again later..");
                }
            });
        }
    });
</script>