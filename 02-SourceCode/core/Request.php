<?php
/**
 * Manage the requests of the user
 * 
 * Define which route was called and redirect to the current page
 */
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
    public static string $cssLinkReturn = "";
    public static array $params = [];

    /**
     * Get the url of the server request uri
     * 
     * @return string => Url of the request uri
     */
    public static function GetUrl() : string
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
     * Check if the url finish by a slash
     * 
     * @param url => Url to check
     * @return string => Url with a slash at the end
     */
    public static function CheckIfUrlFinishBySlash(string $url) : string
    {
        if (substr($url, -1) != "/") 
            return $url."/";
        return $url;
    }

    /**
     * Get the route selected by the url
     * 
     * @param url => url of the request
     * @return Route => Actual route
     */
    public static function GetRoute(string $url) : Route
    {
        Request::$params = [];

        // Set the CSS return
        $uri = str_replace("/", " ", $url);
        $cssReturns = substr_count($uri, "/") - 1;
        for ($i = 0; $i < $cssReturns; $i++) 
            Request::$cssLinkReturn .= "../";
        
        // Check if the url finish by a slash
        $url = Request::CheckIfUrlFinishBySlash($url);
        
        $dynamicRoute = null;
        // Browse all the routes by one and callback at the request url or display error
        foreach (Route::$routes as $key => $route) 
        {       
            // Check if the link of actual route is as the url requested
            if ($route->link == $url) 
                return $route;
            elseif (strpos($route->link, "{")) 
            {
                // Get the pattern of the route
                $pattern = Request::CheckIfUrlFinishBySlash(preg_replace('/{[\w]+}/', '([\w-]+)', $route->link)); 
                $patternWithoutLanguage = Request::CheckIfUrlFinishBySlash(preg_replace('/\/\(\[\\\w-\]\+\)/', '', $pattern, 1));

                // Check if the url match with the pattern
                if (preg_match("#^".$pattern."$#", $url, $matches))
                {         
                    // Remove the first element of the array and get the matches
                    array_shift($matches);
                    preg_match_all('/{([\w]+)}/', $route->link, $paramNames);

                    // Get the names of the parameters
                    foreach ($paramNames[1] as $index => $name) 
                    {
                        Request::$params[$name] = $matches[$index];
                    }

                    $dynamicRoute = $route;
                }
                elseif(preg_match("#^".$patternWithoutLanguage."$#", $url, $matches))
                    return $route;
            }
        }

        // Check if the dynamic url is set
        if ($dynamicRoute != null) 
            return $dynamicRoute;

        return Route::GetRouteByName("404");
    }
}

?>