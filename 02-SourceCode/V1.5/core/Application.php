<?php

namespace app\core;

use app\core\routing\Route;
use app\routes\Web;

class Application
{
    private static Application $instance;          // Instance of the application
    private Route $route;                          // Actual route 

    private function __construct()
    {
        
    }

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

    public function Run()
    {
        // Set the routes
        Web::Set();

                
    }

    public function Login()
    {

    }

    public function Logout()
    {
        
    }
}

?>