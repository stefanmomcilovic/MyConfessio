document.addEventListener("DOMContentLoaded", function(){
    // Doesn't show (Prevent Page)
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
});