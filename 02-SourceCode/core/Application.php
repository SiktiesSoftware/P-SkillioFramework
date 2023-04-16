<?php

use LDAP\Result;

include_once __DIR__."/controllers/MainController.php";
include_once __DIR__."/Request.php";

/**
 * Manage the application routing, redirect, etc...
 */
class Application
{
    private MainController $mainController;        // Main controller object
    private static Application $instance;          // Instance of the application

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
     * Run the application and get the requested url / Route
     */
    public function Run() : void
    {
        $request = new Request();
        $this->mainController->Dispatch($request);
    }
}
?>