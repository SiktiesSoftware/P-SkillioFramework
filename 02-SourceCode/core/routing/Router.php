<?php
/**
 * Manage the router
 */
class Router
{
    public static array $routes;                            // Array of all the routes

    /**
     * Add a route in the array of routes
     */
    public static function AddRoute(Route $route)
    {
        self::$routes[] = $route;
    }

    /**
     * Get a route by name
     * 
     * @param name => Name of the route
     * 
     * @return Route => Route found
     */
    public static function GetRouteByName(string $name) : Route
    {
        // Browse all the routes by one and callback at the request url or display error
        foreach (self::$routes as $key => $route) 
        {
            // Check if the link of actual route is as the url requested
            if ($route->name !== $name) continue;

            // Return the route and set the found variable to true
            return $route;
        }
    }
}
?>