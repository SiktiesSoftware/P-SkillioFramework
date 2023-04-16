<?php
include_once __DIR__."/Router.php";

/**
 * Manages the routes
 */
class Route extends Router
{
    public string $name, $link, $method;                    // Name, link anf Method of the route
    public array $action;                                   // Action of the route
    public ?array $middlewares, $verifications;             // Array of possibles middlewares and verifications

    /**
     * Class constructor
     * 
     * @param link => Link of the route
     * @param action => action of the route
     * @param ?file => File of the view for the route
     * @param ?folder => Folder of the view for the route
     */
    public function __construct(string $link, string $method)
    {
        $this->link = $link;
        $this->method = $method;
    }

    /**
     * Default route
     * 
     * @param link => Link of the route
     * @param action => action of the route
     * @param ?file => File of the view for the route
     * @param ?folder => Folder of the view for the route
     */
    public static function Default(string $link, $action) : Route
    {
        $route = new Route($link, "DEFAULT");
        $route->ActionMethod($action);
        parent::AddRoute($route);
        return $route;
    }

    /**
     * Redirection only route
     */
    public static function Redirect()
    {

    }

    /**
     * Get method route
     * 
     * @param link => Link of the route
     * @param action => action of the route
     * @param ?file => File of the view for the route
     * @param ?folder => Folder of the view for the route
     */
    public static function Get(string $link, $action) : Route
    {
        $route = new Route($link, "GET");
        $route->ActionMethod($action);
        parent::AddRoute($route);
        return $route;
    }

    /**
     * Post method route
     * 
     * @param link => Link of the route
     * @param action => action of the route
     * @param ?file => File of the view for the route
     * @param ?folder => Folder of the view for the route
     */
    public static function Post(string $link, $action) : Route
    {
        $route = new Route($link, "POST");
        $route->ActionMethod($action);
        parent::AddRoute($route);
        return $route;
    }

    /**
     * Define the action method in the array
     * 
     * @param action => action of the route
     */
    private function ActionMethod($action)
    {
        // Check if the action is a callable (anonym function)
        if (is_callable($action)) 
        {
            $this->action["type"] = "anonym";
            $this->action["controller"] = null;
            $this->action["function"] = $action;
        } // Check if the action is a string
        else if (is_string($action))
        {
            // Set the action type
            $this->action["type"] = "string";

            // Check if the action contains a @
            if (str_contains($action, "@")) 
            {
                // Explode the action to get the class and the function
                $actionParts = explode("@", $action);
                // Get the controller
                $this->action["controller"] = $actionParts[0];
                // Get the function
                $this->action["function"] = $actionParts[1];
            }
        } // Check if the action is an array
        else if(is_array($action))
        {
            // Set the action type
            $this->action["type"] = "array";
            // Get the controller
            $this->action["controller"] = $action[0];
            // Get the function
            $this->action["function"] = $action[1];
        }

        self::$routes[] = $this;
    }

    /**
     * Groupe of multiple routes
     */
    public static function Group(string $link, $action)
    {

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

}
?>