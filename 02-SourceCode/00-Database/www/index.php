<?php
// Include controllers
include_once __DIR__."/src/controllers/MainController.php";

$mainController = new MainController();
$mainController->run();
?>