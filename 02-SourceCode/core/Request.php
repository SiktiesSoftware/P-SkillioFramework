<?php
class Request
{
    /**
     * Number of time the link of css need return to display style in all the pages of the website
     * Exemple : 
     *      localhost://8080/               => link/main.css
     *      localhost://8080/admin/users    => ../link/main.css
     * With this semi framework (native languages) we need to implement CSS like that
     * <link rel="stylesheet" href="<?= Request::$cssLinkReturn ?>link/main.css">
     */
    public static $cssLinkReturn;

    /**
     * Get the url of the server request uri
     * 
     * @return pageUri => Url of the request uri
     */
    public static function GetUrl()
    {
        // Get the url request
        $url = $_SERVER["REQUEST_URI"];

        $pageUrl = $url;  // Definitive url of the page

        // Get the page url
        $position = strpos($url, '?');
        if ($position !== false) 
        {
            $pageUrl = substr($url, 0, $position);
        }

        return $pageUrl;
    }

    /**
     * Get the route selected by the url
     * 
     * @param url => url of the request
     * @return route => Actual route
     */
    public static function GetRoute($url) : Route
    {
        // Set the CSS return
        $uri = str_replace("/projects/P-SkilioFramework/02-SourceCode", " ", $url);
        $cssReturns = substr_count($uri, "/") - 1;
        for ($i = 0; $i < $cssReturns; $i++) 
        { 
            Request::$cssLinkReturn .= "../";
        }

        $found = false; // Define if the url is a created one or not (Route => Web.php)
        
        // Browse all the routes by one and callback at the request url or display error
        foreach (Route::$routes as $key => $route) 
        {
            
            // Check if the link of actual route is as the url requested
            if ("/projects/P-SkilioFramework/02-SourceCode".$route->link !== $url) continue;


            // Return the route and set the found variable to true
            $found = true;
            return $route;
        }
        
        // Check if the url is not found and display error
        if (!$found) 
        {
            // Return 404 error
            return Route::GetRouteByName("404");
        }

    }
}

?>