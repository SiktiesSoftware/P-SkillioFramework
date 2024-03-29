<?php
include_once __DIR__."/routing/Route.php";

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
    public static $cssLinkReturn;
    public string $pageUrl;             // Page url 
    public ?Route $route;               // Possible actual route
    public ?array $datas, $errors;      // Possible datas and errors from a form
    public bool $isValid;               // Define if the request is valid

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->pageUrl = $this->GetUrl();
        $this->route = $this->GetRoute($this->pageUrl);
    }

    /**
     * Get the url of the server request uri
     * 
     * @return string => Url of the request uri
     */
    private function GetUrl() : string
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

        // Set the CSS return
        $uri = str_replace("/", " ", $url);
        $cssReturns = substr_count($uri, "/") - 1;
        for ($i = 0; $i < $cssReturns; $i++) 
        { 
            Request::$cssLinkReturn .= "../";
        }

        return $pageUrl;
    }

    /**
     * Get the route selected by the url
     * 
     * @param url => url of the request
     * @return ?Route => Actual route
     */
    private function GetRoute($url) : ?Route
    {
        $found = false; // Define if the url is a created one or not (Route => Web.php)

        // Set all the routes
        include __DIR__."/../routes/Web.php";

        // Browse all the routes by one and callback at the request url or display error
        foreach (Router::$routes as $key => $route) 
        {
            // Check if the link of actual route is as the url requested
            if ($route->link !== $url) continue;

            // Return the route and set the found variable to true
            $found = true;
            return $route;
        }
        
        // Check if the url is not found and display error
        if (!$found) 
        {
            // Return 404 error
            //header("location: ".Route::GetRouteByName("404")->link);
        }

    }
}

?>