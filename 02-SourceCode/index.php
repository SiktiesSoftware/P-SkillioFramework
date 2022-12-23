<?php
// Start the session
session_start();

// Include session and Application class
include_once __DIR__."/core/Session.php";
include_once __DIR__."/core/Application.php";

// Set the session array
Session::SetSession();

// Create a new application and run them
$app = Application::GetInstance();
$app->Run();
?>