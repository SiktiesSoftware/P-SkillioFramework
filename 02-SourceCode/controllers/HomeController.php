<?php
include_once __DIR__."/../core/controllers/Controller.php";
include_once __DIR__."/../core/global/view.php";

/**
 * Manage the home pages and the communication of Views and models
 */
class HomeController extends Controller
{
    public function Home()
    {
        return view("home->Home")->WithDatas(
            [
                "test" => "toto"
            ]
        )->Parse();
    }

    public function Contact()
    {
        
    }
}
?>