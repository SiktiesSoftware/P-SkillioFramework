<?php
include_once "controllers/content/HomeController.php";
include_once "controllers/content/UserController.php";
include_once "controllers/content/ErrorController.php";
include_once "routes/Route.php";

function Web()
{
    if (isset(Route::$routes)) 
    {
        Route::$routes = null;
    }
    
    Route::Set('/', 
        [HomeController::class, 'Home'],
        'home', 'Home.php'
        )->Name('home');

    Route::Set('/contact', 
        [HomeController::class, 'contact'],
        'home', 'contact.php'
        )->Name('contact');

    Route::Set('/404', 
        [ErrorController::class, 'E404'],
        'errors', '404.php'
        )->Name('404');
}
?>