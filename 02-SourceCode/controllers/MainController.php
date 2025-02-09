<?php
include_once __DIR__."/../core/Controller.php";
include_once __DIR__."/content/ErrorController.php";
include_once __DIR__."/content/HomeController.php";
include_once __DIR__."/content/UserController.php";
include_once __DIR__."/content/AccountController.php";
include_once __DIR__."/content/LanguageController.php";
include_once __DIR__."/../core/Component.php";

/**
 * manage the display of the pages and manage the controllers
 */
class MainController
{
    protected string $views = "pages/views/";       // Folder of the views
    protected string $folder;                       // Folder of the view
    protected string $file;                         // View file

    /**
     * Dispatch datas into display page
     * 
     * @param controller => Controller which manage the display
     * @param callable => Callable function which be call to get datas and display page
     * @param link => Link of the route (URL)
     * @param folder =>  Folder of the route 
     * @param file => File of the route (Example.php)
     * @param name => Name of the route
     */
    public function Dispatch($controller, string $callable, $link, $folder, $file, $name) 
    {
        // Check if the controller is set or no
        if (!isset($controller)) 
        {
            $defaultRoute = include(__DIR__."/../.config/defaultRoute.config.php");
            $controller = $defaultRoute["controller"];
            $callable = $defaultRoute["callable"];
            $link = $defaultRoute["link"];
            $folder = $defaultRoute["folder"];
            $file = $defaultRoute["file"];
            $name = $defaultRoute["name"];
        }

        // Get the controller and display page
        $currentController = $this->GetController($controller);
        $this->viewBuild($currentController, $callable, $folder, $file, $link);
    }

    /**
     * Get a controller
     * 
     * @param controller => Controller class
     * 
     * @return link => Controller object
     */
    private function GetController($controller)
    {
        // Check which controller to return
        switch ($controller) 
        {
            case HomeController::class:
                $link = new HomeController();
                break;
            case UserController::class:
                $link = new UserController();
                break;
            case ErrorController::class:
                $link = new ErrorController();
                break;
            case AccountController::class:
                $link = new AccountController();
                break;
            case LanguageController::class:
                $link = new LanguageController();
                break;
            default:
                $link = null;
                break;
        }

        // Return link
        return $link;
    }

    /**
     * Build the view for display pages
     *
     * @param currentController => Current controller
     * @param callable => callable function
     * @param folder => Folder of the view
     * @param file => File of the view
     */
    private function viewBuild($currentController, string $callable, $folder, $file, $link) 
    {
        // Get the content from the current controller
        $display_result = Controller::Display($callable, $folder, $file, $currentController, $link);
        // Check if an error occured or not and display the page
        if ($currentController::class == ErrorController::class) 
        {
            // Include the head
            include(dirname(__FILE__) . '/../pages/includes/head.php');
            
            // Display the page content
            echo $display_result["content"];
        } 
        else if($display_result["api"]) 
        {
            // Display the api content
            echo $display_result["content"];
        } 
        else 
        {
            // Include the head
            include(dirname(__FILE__) . '/../pages/includes/head.php');

            // Include the header
            include(dirname(__FILE__) . '/../pages/includes/header.php');

            // Include the navigation
            include(dirname(__FILE__) . '/../pages/includes/nav.php');
            
            // Display the page content
            echo $display_result["content"];

            // Include the footer
            include(dirname(__FILE__) . '/../pages/includes/footer.php');
        }
    }
}

?>