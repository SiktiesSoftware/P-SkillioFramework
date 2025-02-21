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
    public static function Display(string $callable, string $folder, string $file, mixed $controller, string $link) : mixed
    {
        // Set the folder and the file
        $controller->folder = $folder;
        $controller->file = $file;

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
            $dataRequest = new DataRequest();
            $dataRequest->datas = $_POST;
            
            // Call the controller function with dataRequest
            return $controller->$callable($dataRequest);
        }

        // Call the controller function
        return call_user_func(array($controller, $callable));
    }

}
?>