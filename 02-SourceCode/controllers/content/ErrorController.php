<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/View.php";

/**
 * Manage error pages
 */
class ErrorController extends Controller
{
    /**
     * Display the 403 error page
     * 
     * @return view => View page of error
     */
    public function E403() 
    {
        return ["content" => View::GetByName("403")->Parse(), "api" => false];
    }

    /**
     * Display the 404 error page
     * 
     * @return view => View page of error
     */
    public function E404()
    {
        return ["content" => View::GetByName("404")->Parse(), "api" => false];
    }
}

?>