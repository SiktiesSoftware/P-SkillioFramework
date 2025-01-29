<?php
include_once __DIR__."/RoutingBase.php";

class Route extends RoutingBase 
{
    public string $link, $folder, $file, $name, $method;
    public array $params, $controllerDestination;

    public static array $routes = [];   // Array of routes

    public function __construct(string $link, string $folder, string $file, array $controllerDestination, array $params)
    {
        $this->link = $link;
        $this->folder = $folder;
        $this->file = $file;
        $this->controllerDestination = $controllerDestination;
        $this->params = $params;
    }

    /**
     * Set a new route and add them to the array
     * 
     * @param link => Link of the route
     * @param folder => Folder of the route
     * @param file => File of the route
     * @param function => Function of the route
     * 
     * @return Route => New route
     */
    public static function Get(string $link, array $controllerDestination, string $file, string $folder, $params) : Route
    {
        // Set a new route and add them to the array
        $route = new Route($link, $folder, $file, $controllerDestination, $params);
        $route->method = "GET";
        Route::$routes[] = $route;

        // return the actual route
        return $route;
    }
}
?>