<?php 
include_once __DIR__."/Request.php";
include_once __DIR__."/../routes/web.php";

class Application
{
    private static Application $instance;          // Instance of the application

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
        // Get the requested url
        $url = Request::GetUrl();
        $route = Request::GetRoute($url);
        echo $route->link;

    }
}
?>