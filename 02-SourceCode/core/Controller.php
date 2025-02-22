<?php
include_once __DIR__."/../controllers/content/ErrorController.php";
include_once __DIR__."/../controllers/content/HomeController.php";
include_once __DIR__."/../controllers/content/UserController.php";
include_once __DIR__."/../controllers/content/LanguageController.php";
include_once __DIR__."/form/DataRequest.php";

/**
 * Manage the controllers base
 * 
 * Define which controller must be called
 */
class Controller
{
    public static array $permissions;                     // Permissions to access some page
    protected string|null $folder, $file, $pageName;      // Folder and file of the view

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
    public static function Display(string $callable, string|null $folder, string|null $file, mixed $controller, string $link, string|null $name) : mixed
    {
        // Set the folder and the file
        $controller->folder = $folder;
        $controller->file = $file;
        $controller->pageName = $name;

        // Check if the route link contains "/verify"
        if (str_contains($link, "/verify")) 
        {
            // Check if the post variable is set
            if (!isset($_POST) || count($_POST) <= 0) 
            {
                // Redirect to the home page
                header("location: ".Route::GetRouteByName("home")->GetLink());
            }

            // Set the dataRequest
            $dataRequest = new DataRequest($_POST);
            
            // Call the controller function with dataRequest
            return $controller->$callable($dataRequest);
        }

        // Call the controller function
        return call_user_func(array($controller, $callable));
    }

}
?>