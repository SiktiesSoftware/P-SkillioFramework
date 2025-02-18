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
        Route::Post('/', 
            ["controller" => HomeController::class, "function" => 'Home'],
            'home', 'Home.php'
            )->Name('home');

        // Books page
        Route::Get('/books/{id}/page/{page}', 
            ["controller" => HomeController::class, "function" => 'Books'],
            'home', 'Books.php'
            )->Name('books');

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

        Route::Get('/language', 
            ["controller" => LanguageController::class, "function" => 'GetLanguage'],
            '', ''
            )->Name('language');

        /**
         * Account
         */
        Route::Post('/account/connection', 
            ["function" => 'Connection'],
            'account', 'Connection.php'
            )->Name('connection');

        Route::Post('/account/inscription', 
            ["function" => 'Inscription'],
            'account', 'Inscription.php'
            )->Name('inscription');

        Route::Post('/account/disconnect', 
            ["function" => 'Disconnect'],
            '', ''
            )->Name('disconnect');

        /**
         * Verifications
         */
        // Login
        Route::Post('/login', 
            ["function" => 'verifyLogin'],
            '', ''
            )->Name('verifyLogin');

        Route::Post('/signin', 
            ["function" => 'VerifySignIn'],
            '', ''
            )->Name('VerifySignIn');

        /**
         * Errors
         */
        // 404 page
        Route::Post('/404', 
            ["function" => 'E404'],
            'errors', '404.php'
            )->Name('404');

        // 403 page
        Route::Post('/403', 
            ["function" => 'E403'],
            'errors', '403.php'
            )->Name('403');
    }

    /**
     * Set the groups
     * 
     * GROUP => Account
     * GROUP => Error
     */
    public static function Groups()
    {
        // Account group
        Route::Group(AccountController::class, 
        [
            Route::GetRouteByName("connection"),
            Route::GetRouteByName("inscription"),
            Route::GetRouteByName("disconnect")
        ])->Name("Account");

        // Error group
        Route::Group(ErrorController::class,
        [
            Route::GetRouteByName("403"),
            Route::GetRouteByName("404")
        ])->Name("Error");
    }

    /**
     * Set all the middlewares
     * 
     * MIDDLEWARE => Users
     */
    public static function Middlewares()
    {
        Route::Middleware(UserController::class,
        [
            // Middleware class
            Auth::class => 
            [
                "IsConnected" => [],
                "HasPermission" =>
                [
                    "Roles" => ["admin"]
                ]
            ],
        ],
        [
            Route::GetRouteByName("users"),
            Route::GetRouteByName("user")
        ])->Name("users");
    }

    /**
     * Set all the verifications
     */
    public static function Verifications()
    {
        Route::Verification(AccountController::class,
        [
            Route::GetRouteByName("verifyLogin"),
            Route::GetRouteByName("VerifySignIn")
        ])->Name("verifyAuth");
    }
}
?>