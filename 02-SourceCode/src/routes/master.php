<?php
namespace skillio\routes;

use skillio\controllers\subControllers\HomeController;
use skillio\controllers\subControllers\UserController;
use skillio\core\internal\RestrictedAccessFactory;
use skillio\core\routing\Request;
use skillio\core\routing\Response;
use skillio\core\routing\Route;
use skillio\core\routing\Router;
use skillio\core\utils\content\Component;
use skillio\core\utils\content\Content;
use skillio\core\utils\content\View;
use skillio\middlewares\ApiMiddleware;
use skillio\middlewares\AuthMiddleware;
use skillio\middlewares\TestMiddleware;

Route::get(url: "/", callback: function(Request $request) 
{
    $usersLink = Router::getRouteByName(name: "user.create")->link();
    
    return new Response(
        content: View::set("home", [
            "usersLink" => $usersLink
        ]),
        statusCode: 200,
        headers: ["Content-Type" => "text/html"]
    );
})->name(name: "home");

Route::post(url: "/users", callback: [UserController::class, "create"])->name(name: "user.create");

Route::get(url: "/users/test", callback: [UserController::class, "test"])->name(name: "test");

Route::get(url: "/users/{id}", callback: function (Request $request) {
    return new Response(
        content: '<h1>User Page</h1> <hr>{{ $dll }}' . implode(", ", $request->getQueryParams()->map(fn($value, $key) => "$key: $value")),
        statusCode: 200,
        headers: ["Content-Type" => "text/html"]
    );
})->name(name: "user.book");

Route::get(url: "/users/base", callback: function () {
    return new Response(
        content: "<h1>Users base page</h1> <hr> This page has priority over the user/{id} route",
        statusCode: 200,
        headers: ["Content-Type" => "text/html"]
    );
})->name(name: "test");

Route::get(url: "/users/{id}/books/{bookId}", callback: [HomeController::class, "book"])->name(name: "user.book");

Route::view(url: "/*", view: View::set(name: "errors.404"), statusCode: 404)->name(name: "404");

Route::get(url: "/about", callback: [HomeController::class, "about"])->name(name: "about");

Route::get(url: "/about/me", callback: fn(Request $request) => new Response(
    content: Content::set
    (content: '<div class="min-h-screen bg-gray-900 text-white p-8">
                    <h1 class="text-4xl font-bold mb-4">{{ $helloworld }}</h1>
                    <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                        @child.name="about-me"
                    </div>
                </div>'
    )->with(data: ["helloworld" => $request->method()]
    )->children(name: "about-me", content: Component::set(name: "about-me")),
    statusCode: 200,
    headers: ["Content-Type" => "text/html"]
))->name(name: "about");

Route::get(url: "/about/*", callback: fn() => new Response(
    content: "<h1>About Subpage not found</h1> <hr> This is a subpage of the about page that does not exist.",
    statusCode: 404,
    headers: ["Content-Type" => "text/html"]
))->name(name: "about.subpage");

Route::group(controller: HomeController::class,
    routes: [
        Route::get(url: "/information", callback: ["information"])->name(name: "information"),
        Route::get(url: "/contact", callback: ["contact"])->name(name: "contact"),
    ]
)->prefix(prefix: "/home");

Route::middleware(middlewares: [ AuthMiddleware::class, TestMiddleware::class ],
    routes: [
        Route::get(url: "/dashboard", callback: ["dashboard"])->name(name: "dashboard"),
        Route::get(url: "/settings", callback: ["settings"])->name(name: "settings"),
    ]
)->group(HomeController::class
)->prefix(prefix: "/admin");


Route::middleware(middlewares: [ApiMiddleware::class],
    routes: [
        Route::get(url: "/api/information", callback: ["information"])->name(name: "api.information")
    ]
)->group(HomeController::class);
?>