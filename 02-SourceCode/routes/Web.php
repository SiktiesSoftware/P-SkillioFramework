<?php
include_once __DIR__."/../core/global/view.php";

Route::Post("/anonym", function(){
    return "C'est une fonction anonyme";
})->Name("Anonym");

Route::Post("/about", function(){
    return view("About")->WithDatas(
        [
            "title" => "Cette page est à propos de moi",
            "description" => "Bonjour, ...."
        ]
    )->Parse();
})->Name("About");

// ->WithDatas(
//     [
//         "title" => "Cette page est à propos de moi",
//         "description" => "Bonjour, ...."
//     ]
// );

Route::Post("/contact", "HomeController@Contact")->Name("Contact");

Route::Post("/", [HomeController::class, 'Home'])->Name("Home");

// Route::Group("/admin", [AdminController::class, function()
// {
//     Route::Post("/Users", "Users");
//     Route::Post("/Books", "Books");
// }]);

// Route::Group("/admin", [AdminController::class, function()
// {
//  Route::Post("/Users", "Display");
//  Route::Post("/Books", "Books");
// }])->middleware([AccountMiddleware::class => ["first", "second"]]);
?>