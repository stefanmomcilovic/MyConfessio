<?php
    if(!function_exists("noCache")){
        function noCache($path){
        return $path."?v=". filemtime($path);
        }
    }
?>