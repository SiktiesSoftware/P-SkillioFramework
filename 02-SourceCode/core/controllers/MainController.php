<?php

use LDAP\Result;

include_once __DIR__."/Controller.php";
include_once __DIR__."/../../controllers/HomeController.php";

/**
 * manage the display of the pages and manage the controllers
 */
class MainController extends Controller
{
    protected string $views = "pages/views/";       // Folder of the views
    protected string $folder, $file;                       // Folder and file of the View 

    /**
     * Dispatch datas into display page
     * 
     * @param request => Request send on the start
     */
    public function Dispatch(Request $request) 
    {
        // Get the controller and display page
        $currentController = $this->GetController($request->route->action["controller"]);
        $this->viewBuild($currentController, $request);
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
    private function viewBuild($currentController, Request $request) 
    {
        // Get the content from the current controller
        $content = parent::Display($request, $currentController);
        
        // Include the head
        include(dirname(__FILE__) . '/../../pages/includes/head.php');
 
        // Check if an error occured or not and display the page
        if (isset($currentController) && $currentController::class == ErrorController::class) 
        {
            // Display the page content
            echo $content;
        } 
        else 
        {
            // Include the header
            include(dirname(__FILE__) . '/../../pages/includes/header.php');
            // Include the navigation
            include(dirname(__FILE__) . '/../../pages/includes/nav.php');
            // Display the page content
            echo $content;
            // Include the footer
            include(dirname(__FILE__) . '/../../pages/includes/footer.php');
        }
    }
}

// Check if the controller is set or no
// if (!isset($controller)) 
// {
//     // Set the default route
//     $defaultRoute = include(__DIR__."/../.config/defaultRoute.config.php");
//     $controller = $defaultRoute["controller"];
//     $function = $defaultRoute["function"];
//     $link = $defaultRoute["link"];
//     $folder = $defaultRoute["folder"];
//     $file = $defaultRoute["file"];
//     $name = $defaultRoute["name"];

//     // Set the route on the request
//     $request->route->action["controller"] = $controller;
//     $request->route->action["function"] = $function;
//     $request->route->link = $link;
//     $request->route->folder = $folder;
//     $request->route->file = $file;
//     $request->route->name = $name;
// }
?>