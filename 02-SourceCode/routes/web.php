<?php
include_once __DIR__."/../core/routing/Route.php";

/**
 * manage the routes creations
 */
class Web
{
    /**
     * Set all the routes created
     */
    public static function Set()
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
        Route::Post('/', 
            [HomeController::class, 'Home'],
            'home', 'Home.php'
            )->Name('home');

        // Contact page
        Route::Post('/contact', 
            [HomeController::class, 'Contact'],
            'home', 'contact.php'
            )->Name('contact');

        /**
         * Users
         */
        // Users page
        Route::Post('/users', 
            [UserController::class, 'Users'],
            'users', 'Users.php'
            )->Name('users');

        // User page
        Route::Get('/user', 
            [UserController::class, 'User'],
            'users', 'User.php'
            )->Name('user');


        // Connection inscription group
        Route::Group([
            Route::Post('/connection', 
                [UserController::class, 'CConnection'],
                'account', 'Connection.php'
                )->Name('connection'),
            Route::Post('/inscription', 
                [UserController::class, 'Inscription'],
                'account', 'Inscription.php'
                )->Name('inscription')
        ]);


        /**
         * Errors
         */
        // 404 page
        Route::Post('/404', 
            [ErrorController::class, 'E404'],
            'errors', '404.php'
            )->Name('404');
    }
}
?>