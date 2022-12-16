<?php
// Return the informations for the default location
function DefaultRoute()
{
    return array
    (
        "controller" => HomeController::class,
        "callable" => "Home",
        "link" => "/",
        "folder" => "home",
        "file" => "Home.php",
        "name" => "home"
    );
}

?>