<?php
include_once __DIR__."/Base.php";
include_once __DIR__."/../../middlewares/Auth.php";
/**
 * Manage middleware group redirections
 * 
 * @extends Base => Base redirection managing groups
 */
class Middleware extends Base
{
    public array $content;            // Array of function to verify in middlewares
    public $controller;               // Controller of the group
    public static $datas;             // Actual datas for the function

    /**
     * Middleware class constructor
     * 
     * @param routes => Routes associated to the middleware
     * @param content => Array of content which must be executed and successful before redirection
     * @param controller => Controller of the page
     */
    public function __construct(array $routes, $content, $controller)
    {
        // Set the content and call parent
        $this->content = $content;
        $this->controller = $controller;
        Parent::__construct($routes);
    }

    /**
     * Execute the middlewares one by one
     * 
     * @return bool => Define if the user has access to the resource
     */
    public function Exec() : array
    {
        // Get one by one all the class and function of different middlewares
        foreach ($this->content as $class => $functions) 
        {
            // Get each of the functions and datas for the middleware
            foreach ($functions as $function => $datas)
            {
                // Set the datas
                self::$datas = $datas;

                // Call the actual middleware
                $access = call_user_func(array($class, $function));

                // Check if the previous middleware is successful and continue or break the loop
                if ($access["hasAccess"]) continue;
                break;
            }


        }

        // Return the access
        return $access;
    }
}
?>