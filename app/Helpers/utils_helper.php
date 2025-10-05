<?php

if ( ! function_exists('imageExists'))
{
    function imageExists($url) {  
        // Suppress warnings and check if the file can be retrieved  
        $headers = @get_headers($url);  
        
        // Check if the headers were successfully retrieved  
        if ($headers && strpos($headers[0], '200') !== false) {  
            return true; // Image exists  
        }  
        
        return false; // Image does not exist  
    } 

    function text2html($text) {
        $html = str_replace("\n", "<br/>", $text);
        return $html;
    }

    function floatV($text) {
        $f = floatval(preg_replace('/[^0-9.]/', '', trim($text)));
        $f = str_replace(',', '', $f);
        $f = floatval($f);
        return $f;
    }
    function str_find($text, $pattern) {
        $delimiters = "/[,; ]/";
        $array = preg_split($delimiters, $text); 
        foreach($array as $a) {
            if(strtolower($a) == strtolower($pattern)) return true;
        }
        return false;
    }
}
?>