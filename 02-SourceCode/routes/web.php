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
        Route::Post('/', 
            ["controller" => HomeController::class, "function" =>  'Home'],
            'home', 'Home.php'
            )->Name('home');

        // Contact page
        Route::Post('/contact', 
            ["controller" => HomeController::class, "function" => 'Contact'],
            'home', 'contact.php'
            )->Name('contact');

        /**
         * Users
         */
        // Users page
        Route::Post('/users', 
            ["function" => 'Users'],
            'users', 'Users.php'
            )->Name('users');

        // User page
        Route::Get('/user', 
            ["function" => 'User'],
            'users', 'User.php'
            )->Name('user');

        /**
         * Account
         */
        Route::Post('/connection', 
            ["function" => 'Connection'],
            'account', 'Connection.php'
            )->Name('connection');

        Route::Post('/inscription', 
            ["function" => 'Inscription'],
            'account', 'Inscription.php'
            )->Name('inscription');

        /**
         * Errors
         */
        // 404 page
        Route::Post('/404', 
            ["controller" => ErrorController::class, "function" => 'E404'],
            'errors', '404.php'
            )->Name('404');
    }

    /**
     * Set the groups
     */
    public static function Groups()
    {
        // Account group
        Route::Group(AccountController::class, 
        [
            Route::GetRouteByName("connection"),
            Route::GetRouteByName("inscription")
        ])->Name("Account");
    }

    /**
     * Set all the middlewares
     */
    public static function Middlewares()
    {
        Route::Middleware(UserController::class,
        [
            Auth::class => "IsConnected",
            Auth::class => "HasPermission"
        ],
        [
            Route::GetRouteByName("users"),
            Route::GetRouteByName("user")
        ])->Name("users");
    }
}
?>