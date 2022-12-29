<?php
include_once __DIR__."/../controllers/content/ErrorController.php";
include_once __DIR__."/../controllers/content/HomeController.php";
include_once __DIR__."/../controllers/content/UserController.php";

/**
 * Manage the controllers base
 * 
 * Define which controller must be called
 */
class Controller
{
    public static array $permissions;     // Permissions to access some page
    protected string $folder, $file;      // Folder and file of the page to display

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
    public static function Display(string $callable, $folder, $file, $controller)
    {
        $controller->folder = $folder;
        $controller->file = $file;

        return call_user_func(array($controller, $callable));
    }
}
?>