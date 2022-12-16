<?php
include_once "controllers/content/HomeController.php";
include_once "controllers/content/ErrorController.php";
include_once "controllers/content/UserController.php";
require "controllers/DEFAULT.config.php";
require "routes/Web.php";

/**
 * Class alowing to access the database
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
    private function dispatch($controller, string $callable, $link, $folder, $file, $name) 
    {
        // Check if the controller is set or no
        if (!isset($controller)) 
        {
            $defaultRoute = DefaultRoute();
            $controller = $defaultRoute["controller"];
            $callable = $defaultRoute["callable"];
            $link = $defaultRoute["link"];
            $folder = $defaultRoute["folder"];
            $file = $defaultRoute["file"];
            $name = $defaultRoute["name"];
        }

        // Get the controller and display page
        $currentLink = $this->GetController($controller);
        $this->viewBuild($currentLink, $callable, $folder, $file);
    }

    /**
     * Get a controller
     * 
     * @param controller => Controller class
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
     * @param currentLink => Current controller
     */
    private function viewBuild($currentLink, string $callable, $folder, $file) 
    {
        // Get the content from the current controller
        $content = $currentLink->display($callable, $folder, $file);
        
        // Display page
        if (get_class($currentLink) == 'DownloadController') 
        {
            echo $content;
        } 
        else 
        {
            include(dirname(__FILE__) . '/../pages/includes/head.php');
            include(dirname(__FILE__) . '/../pages/includes/header.php');
            include(dirname(__FILE__) . '/../pages/includes/nav.php');
            echo $content;
            include(dirname(__FILE__) . '/../pages/includes/footer.php');
        }
    }

    /**
     * Run the routes and get the requested
     */
    public function run() 
    {
        // Set the routes
        Web();

        // Get the request url 
        $uri = $_SERVER["REQUEST_URI"];
        $found = false;                     // Define if the url is a created one or not (Route => Web.php)

        $debug = false;
        if ($debug) 
        {
            echo "<pre>";
            var_dump(Route::$routes);
            echo "</pre>";
            echo "<pre>";
            var_dump($uri);
            echo "</pre>";
        }
        
        // Browse all the routes by one and callback at the request url or display error
        foreach (Route::$routes as $key => $route) 
        {
            // Check if the link of actual route is as the url requested
            if ($route->link !== $uri) continue;
            
            // Set the url found to true and Callback
            $found = true;
            $this->Callback($route);
        }
        
        // Check if the url is not found and display error
        if (!$found) 
        {
            // Display error
            $this->Error(Route::GetRouteByName("404"));
        }
    }

    /**
     * Execute when url is found and will redirect to request page
     * 
     * @param route => Route send
     */
    private function Callback(Route $route)
    {
        $this->dispatch($route->function[0], $route->function[1], $route->link, $route->folder, $route->file, $route->name);
    }

    /**
     * Execute when url not found and will redirect into error page (404, 403, 500)
     * 
     * @param errorRoute => Error route send
     */
    private function Error(Route $errorRoute)
    {
        $this->dispatch($errorRoute->function[0], $errorRoute->function[1], $errorRoute->link, $errorRoute->folder, $errorRoute->file, $errorRoute->name);
    }
}    
?>