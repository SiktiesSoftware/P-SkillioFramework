<?php
include_once __DIR__."/Group.php";
include_once __DIR__."/Middleware.php";
include_once __DIR__."/Verification.php";

/**
 * Manage the routes
 */
class Route
{
    public string $link, $folder, $file, $name, $method;       // Link, folder, file and name of the route
    public array $function;                                     // Function of the assigned controller

    public static array $routes;                                // Array of routes
    public static array $groups;                                // Array of groups
    public static array $middlewares;                           // Array of middlewares
    public static array $verifications;                         // Array of middlewares

    /**
     * Route class constructor
     * 
     * @param link => Link of the route (URL)
     * @param folder => Folder of the route 
     * @param file => File of the route
     * @param function => Function of the route
     */
    private function __construct(string $link, string $folder, $file, array $function)
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
        $route->method = "GET";
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
        $route->method = "POST";
        Route::$routes[] = $route;

        // return the actual route
        return $route;
    }

    /**
     * Set a group of few routes
     * 
     * @param routes => Routes entered on the group
     * @param controller => Controller of the group
     * 
     * @return Group => Group created
     */
    public static function Group($controller, array $routes) : Group
    {
        // Set the controller of the group to the routes
        foreach ($routes as $key => $route)
        {
            $routes[$key]->function["controller"] = $controller;
        }

        // Set a new group and add them to the array + return them
        $group = new Group($routes);
        Route::$groups[] = $group;
        return $group;
    }

    /**
     * Set a middleware of few routes
     * 
     * @param controller => Controller of the middleware
     * @param file => File name of the selected middleware
     * @param routes => Routes entered on the middleware
     * 
     * @return Middleware => Middleware created
     */
    public static function Middleware($controller, array $function, $routes) : Middleware
    {
        // Set the controller of the group to the routes
        foreach ($routes as $key => $route)
        {
            $routes[$key]->function["controller"] = $controller;
        }

        // Set a new group and add them to the array
        $middleware = new Middleware($routes, $function, $controller);
        Route::$middlewares[] = $middleware;
        return $middleware;
    }

    /**
     * Set a verifications of few routes
     * 
     * @param controller => Controller of the verification
     * @param file => File name of the selected verification
     * @param routes => Routes entered on the verification
     * 
     * @return Verification => verification created
     */
    public static function Verification($controller, $routes) : Verification
    {
        // Set the controller of the group to the routes
        foreach ($routes as $key => $route)
        {
            $routes[$key]->function["controller"] = $controller;
            $routes[$key]->link = "/verify".$route->link;
        }

        // Set a new group and add them to the array
        $verification = new Verification($routes);
        Route::$verifications[] = $verification;
        return $verification;
    }

    /**
     * Set the name of the route
     * 
     * @param name => name of the route
     */
    public function Name(string $name) : void
    {
        $this->name = $name;
    }

    /**
     * Get a route by his name
     * 
     * @param name => name of the route
     * 
     * @return Route => Route requested
     */
    public static function GetRouteByName(string $name) : Route
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

    /**
     * Get a group by his name
     * 
     * @param name => name of the route
     * 
     * @return Group => Group requested
     */
    public static function GetGroupByName(string $name) : Group
    {
        // Browse all routes by one and return the one who has the same name
        foreach (Route::$groups as $key => $group) 
        {
            // Check if the actual route has the same name that entered
            if ($group->name !== $name) continue;

            // Return the route
            return $group;
        }
    }

    /**
     * Get a middleware by his name
     * 
     * @param name => name of the route
     * 
     * @return Group => Group requested
     */
    public static function GetMiddlewareByName(string $name) : Middleware
    {
        // Browse all routes by one and return the one who has the same name
        foreach (Route::$middlewares as $key => $middleware) 
        {
            // Check if the actual route has the same name that entered
            if ($middleware->name !== $name) continue;

            // Return the route
            return $middleware;
        }
    }

    /**
     * Get a group by his name
     * 
     * @param name => name of the route
     * 
     * @return Group => Group requested
     */
    public static function GetVerificationByName(string $name) : Verification
    {
        // Browse all routes by one and return the one who has the same name
        foreach (Route::$verifications as $key => $verification) 
        {
            // Check if the actual route has the same name that entered
            if ($verification->name !== $name) continue;

            // Return the route
            return $verification;
        }
    }
}

?>