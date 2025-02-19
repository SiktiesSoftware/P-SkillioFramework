<?php
include_once __DIR__."/../controllers/MainController.php";
include_once __DIR__."/routing/Middleware.php";
include_once __DIR__."/../routes/web.php";
include_once __DIR__."/Request.php";

/**
 * Manage the application routing, redirect, etc...
 */
class Application
{
    private MainController $mainController;        // Main controller object
    private static Application $instance;          // Instance of the application
    private Route $route;                          // Actual route 

    /**
     * Application class constructor
     */
    private function __construct()
    {
        // Set maincontroller
        $this->mainController = new MainController();
    }

    /**
     * Get the instance of the application
     * 
     * @return Application => Current application instance
     */
    public static function GetInstance() : Application
    {
        /**
         * Check if the instance of the application is set
         */
        if (!isset(Application::$instance)) 
        {
            // Create a new object
            Application::$instance = new Application();
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
            header("Access-Control-Allow-Headers: X-Requested-With");
            Lang::Setup();
        }

        // Return application
        return Application::$instance;
    }

    /**
     * Run the routes and get the requested url
     */
    public function Run() : void
    {
        // Set the routes, groups and middlewares
        Web::Routes();
        Web::Groups();
        Web::Middlewares();
        Web::Verifications();

        // Check if the language is set
        if (!isset($_SESSION["lang"])) 
            $_SESSION["lang"] = "en";
        
        // Get the url
        $url = Request::GetUrl();
        $this->route = Request::GetRoute($url);

        // Set the language
        if (!isset(Request::$params["lang"]) || !in_array(Request::$params["lang"], Lang::$languages))
            header("location:".$this->route->GetLink());
        
        $_SESSION["lang"] = Request::$params["lang"];

        // Check if a middleware needs to be called
        if (isset(Route::$middlewares)) 
        {
            // Get middlewares object one by one
            foreach (Route::$middlewares as $key => $middleware) 
            {
                // Get routes of the middleware one by one
                foreach ($middleware->routes as $key => $route) 
                {
                    // Check if the sended route is equal to a middleware route
                    if ($route != $this->route) continue;

                    // Execute the middlewares
                    $access = $middleware->Exec();
                    
                    // Check if the middleware is successful
                    if ($access["hasAccess"] == false) 
                    {   
                        // Set the route to another set in the middleware
                        $this->route = $access["Request"];
                        header("location: ".$this->route->GetLink());
                    }
                    break;
                }
            }
        }
        // Dispatch functions
        $this->mainController->Dispatch($this->route->function["controller"], $this->route->function["function"], $this->route->GetLink(), $this->route->folder, $this->route->file, $this->route->name);
    }
}

?>