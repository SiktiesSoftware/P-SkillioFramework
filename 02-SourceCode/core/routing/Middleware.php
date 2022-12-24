<?php
include_once __DIR__."/Base.php";
/**
 * Manage middleware group redirections
 * 
 * @extends Base => Base redirection managing groups
 */
class Middleware extends Base
{
    public array $functions;            // Array of function to verify in middlewares

    /**
     * Middleware class constructor
     * 
     * @param routes => Routes associated to the middleware
     * @param functions => Array of function which must be executed and successful before redirection
     * @param controller => Controller of the page
     */
    public function __construct(array $routes, $functions, $controller)
    {
        $this->function = $functions;
        var_dump($this->functions);
        Parent::__construct($routes);
    }

    /**
     * Execute the middlewares one by one
     * 
     * @return bool => Define if the user has access to the resource
     */
    public function Exec() : bool
    {
        echo "<pre>";
        var_dump($this);
        echo "</pre>";
        die;
        // Get one by one all the class and function of different middlewares
        foreach ($this->functions as $class => $function) 
        {
            // Call the actual middleware
            $hasAccess = call_user_func(array($class, $function));

            // Check if the previous middleware is successful and continue or break the loop
            if ($hasAccess) continue;
            return $hasAccess;
        }
    }
}
?>