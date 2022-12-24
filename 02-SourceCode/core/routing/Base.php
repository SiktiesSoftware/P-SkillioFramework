<?php
/**
 * Manage the groups and middlewares
 * 
 * To explain, consider that a Base object is for groups
 * A middleware is considered a group
 */
class Base
{
    public string $name;                // Name of the group
    public array $routes;               // Routes on the group

    /**
     * Group class constructor
     * 
     * @param routes => Routes selected for these group
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Get the route selected by name
     * 
     * @param name => Name of the selected route
     * 
     * @return Route => Actual selected route
     */
    public function GetRoute(string $name) : Route
    {
        // Check for each route if the name is same as sended
        foreach ($this->routes as $key => $route) 
        {
            // Check a route
            if ($route->name != $name) continue;

            // Return the selected route
            return $route;
        }
    }   

    /**
     * Set the name of the group
     * 
     * @param name => Name of the group
     * 
     * @return Base => Actual group
     */
    public function Name(string $name) : Base
    {
        // Set the name of the group
        $this->name = $name;
        return $this;
    }
}
?>