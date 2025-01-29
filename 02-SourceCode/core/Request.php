<?php
class Request
{
    public static function getUrl() : string    
    {
        // Get the url request
        $url = $_SERVER["REQUEST_URI"];

        $page = $url;  // Definitive url of the page

        // Get the page url
        $position = strpos($url, '?');
        if ($position !== false) 
        {
            $page = substr($url, 0, $position);
        }

        return $page;
    }
}
?>