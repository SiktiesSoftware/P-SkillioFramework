<?php

class Route
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
     * Set the name of the route
     * 
     * @param name => name of the route
     */
    public function name(string $name) : void
    {
        $this->name = $name;
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
    
    /**
     * Get a route by his name
     * 
     * @param name => name of the route
     * 
     * @return Route => Route requested
     */
    public static function getRouteByName(string $name) : Route
    {
        // Browse all routes by one and return the one who has the same name
        foreach (Route::$routes as $key => $route) 
        {
            // Check if the actual route has the same name that entered
            if ($route->name !== $name) continue;

            // Return the route
            return $route;
        }
    }
}
?>