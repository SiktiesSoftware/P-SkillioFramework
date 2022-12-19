<?php
namespace app\core;

/**
 * Manage the routes
 */
class Route
{
    public string $link, $folder, $file, $name, $method;        // Link, folder, file and name of the route
    public array $function;                                     // Function of the assigned controller

    public static array $routes;                                // Array of routes

    /**
     * Route class constructor
     * 
     * @param link => Link of the route (URL)
     * @param folder => Folder of the route 
     * @param file => File of the route
     * @param function => Function of the route
     */
    private function __construct(string $link, string $folder, string $file, array $function)
    {
        $this->link = $link;
        $this->folder = $folder;
        $this->file = $file;
        $this->function = $function;
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
    public static function Get(string $link, array $function, string $file, $folder) : Route
    {
        // Set a new route and add them to the array
        $route = new Route($link, $file, $folder, $function);
        $route->method = "get";
        Route::$routes[] = $route;

        // return the actual route
        return $route;
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
    public static function Post(string $link, array $function, string $file, $folder) : Route
    {
        // Set a new route and add them to the array
        $route = new Route($link, $file, $folder, $function);
        $route->method = "post";
        Route::$routes[] = $route;

        // return the actual route
        return $route;
    }

    /**
     * Set the name of the route
     * 
     * @param name => name of the route
     */
    public function Name($name)
    {
        $this->name = $name;
    }

    /**
     * Get a route by his name
     * 
     * @param name => name of the route
     * @return Route => Route requested
     */
    public static function GetRouteByName($name)
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