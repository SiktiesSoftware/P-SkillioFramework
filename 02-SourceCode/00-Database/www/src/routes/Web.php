<?php
include_once __DIR__."/../controllers/content/HomeController.php";
include_once __DIR__."/../controllers/content/UserController.php";
include_once __DIR__."/../controllers/content/ErrorController.php";
include_once __DIR__."/Route.php";
include_once __DIR__."/Methods.php";

function Web()
{
    if (isset(Route::$routes)) 
    {
        Route::$routes = null;
    }
    
    Route::Set('/', 
        [HomeController::class, 'Home'],
        'home', 'Home.php',
        Method::POST
        )->Name('home');

    Route::Set('/contact', 
        [HomeController::class, 'Contact'],
        'home', 'contact.php',
        Method::POST
        )->Name('contact');

    Route::Set('/users', 
        [UserController::class, 'Users'],
        'users', 'Users.php',
        Method::POST
        )->Name('contact');

    Route::Set('/user', 
        [UserController::class, 'User'],
        'users', 'User.php',
        Method::GET
        )->Name('user');

    Route::Set('/404', 
        [ErrorController::class, 'E404'],
        'errors', '404.php',
        Method::POST
        )->Name('404');
}
?>