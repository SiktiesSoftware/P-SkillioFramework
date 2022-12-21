<?php
include_once __DIR__."/../controllers/MainController.php";
include_once __DIR__."/../routes/web.php";
include_once __DIR__."/Request.php";

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
     * @return application => Current application instance
     */
    public static function GetInstance()
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
    public function Run()
    {
        // Set the routes
        Web::Set();

        // Get the url
        $url = Request::GetUrl();
        $this->route = Request::GetRoute($url);

        // Dispatch functions
        $this->mainController->Dispatch($this->route->function[0], $this->route->function[1], $this->route->link, $this->route->folder, $this->route->file, $this->route->name);
    }

    /**
     * Login to the user
     */
    public function Login()
    {

    }

    /**
     * Logout to the user
     */
    public function Logout()
    {
        
    }
}

?>