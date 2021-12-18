<?php 
    function elipsis($str, $len){
        if(strlen($str) > $len){
            return substr($str, 0, $len - 3).'...';
        }
        return $str;
    }

?>