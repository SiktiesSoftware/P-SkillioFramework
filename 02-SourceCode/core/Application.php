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

        // Get the url
        $url = Request::GetUrl();
        $this->route = Request::GetRoute($url);

        $_POST["test"] = "toto";

        // Check if the route link contains "/verify"
        if (str_contains($this->route->link, "/verify")) 
        {
            // Check if the post variable is set
            if (!isset($_POST) || count($_POST) <= 0) 
            {
                header("location: ".Route::GetRouteByName("home")->link);
            }
        }

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
                        header("location: ".$this->route->link);
                    }
                    break;
                }
            }
        }
        // Dispatch functions
        $this->mainController->Dispatch($this->route->function["controller"], $this->route->function["function"], $this->route->link, $this->route->folder, $this->route->file, $this->route->name);
    }
}

?>