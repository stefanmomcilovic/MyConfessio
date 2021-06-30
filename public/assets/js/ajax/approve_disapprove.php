<script type="text/javascript">
$(document).ready(function(){
    var is_value_clicked = 0; // Created a variable that's count how many time user click on approve or disapprove
    $(".action").click(function(e) {
        e.preventDefault();
        var clicked_value = $(this).val().toLowerCase(); // Clicked value approve or disapprove
        var confess_id = $(this).attr("data-confess-id"); // Getting confession id
        is_value_clicked += 1; // Incrising is clicked value by 1
        $.ajax({ 
            url: "<?= site_url("confess_actions") ?>",
            type: "POST",
            data: {
                clicked_value: clicked_value,
                confess_id: confess_id,
                is_clicked: is_value_clicked
            },
            success: function(response) {
                var res = JSON.parse(response);
                if (res.confess_id == confess_id && res.action == "approve") { // If clicked value is approe to do this
                    $(".approve-" + confess_id).addClass("action-active");
                    $(".disaprove-" + confess_id).removeClass("action-active");
                } else if (res.action == "disaprove") { // Else if click value is disapprove to do this
                    $(".disaprove-" + confess_id).addClass("action-active");
                    $(".approve-" + confess_id).removeClass("action-active");
                }
            },
            error: function(){
                alert("Sorry, there was some error, try again later..");
            }
        });
    });
});
</script>