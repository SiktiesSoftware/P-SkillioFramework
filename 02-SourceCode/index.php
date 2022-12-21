<?php
session_start();

include_once "core/Application.php";

$app = Application::GetInstance();
$app->Run();
?>