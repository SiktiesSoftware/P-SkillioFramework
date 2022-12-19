<?php

namespace app\core;

use app\core\routing\Route;

class Application
{
    private static Application $instance;          // Instance of the application
    private Route $route;

    private function __construct()
    {
        
    }

    public static function GetInstance()
    {
        if (!isset(Application::$instance)) 
        {
            Application::$instance = new Application();
        }

        return Application::$instance;
    }

    public function Login()
    {

    }

    public function Logout()
    {
        
    }

    public function Run()
    {

    }
}

?>