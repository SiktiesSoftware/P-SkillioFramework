<?php
include_once __DIR__."/../../controllers/HomeController.php";

/**
 * Manage the controllers base
 * 
 * Define which controller must be called
 */
abstract class Controller
{
    public static array $permissions;     // Permissions to access some page

    /**
     * Execute a function
     * 
     * @param callable => Callable function in the class
     * @param folder => Folder of the view
     * @param file => View file
     * @param controller => controller class
     * 
     * @return function => return the content of a function
     */
    protected static function Display(Request $request, $controller)
    {
        // Check if the controller is set        
        if (isset($controller)) 
        {
            // Return the function of the controller
            return call_user_func(array($controller, $request->route->action["function"]));
        }
        else
        {
            // Return a simple function
            return call_user_func($request->route->action["function"]);
        }
    }
}
?>