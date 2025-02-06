<?php
include_once __DIR__."/../core/routing/Route.php";

/**
 * manage the routes creations
 */
class Web
{
    /**
     * Set all the routes created
     * 
     * ROUTE => home
     * ROUTE => contact
     * ROUTE => users
     * ROUTE => user
     * ROUTE => connection
     * ROUTE => inscription
     * ROUTE => E403
     * ROUTE => E404
     */
    public static function Routes()
    {
        // Check if the routes list is set
        if (isset(Route::$routes)) 
        {
            // Set the routes to null
            Route::$routes = null;
        }
        
        /**
         * Home
         */
        // Home page
        // Route::Get('/', 
        //     ["controller" => HomeController::class, "function" =>  'Home'],
        //     'home', 'Home.php'
        //     )->Name('home');
    }

    /**
     * Set the groups
     * 
     * GROUP => Account
     * GROUP => Error
     */
    public static function Groups()
    {

    }

    /**
     * Set all the middlewares
     * 
     * MIDDLEWARE => Users
     */
    public static function Middlewares()
    {

    }

    /**
     * Set all the verifications
     */
    public static function Verifications()
    {

    }
}
?>