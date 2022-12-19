<?php
include_once __DIR__."/../MainController.php";
include_once __DIR__."/IController.php";

include_once __DIR__."/../../pages/views/View.php";

/**
 * Class alowing to access the database
 */
class HomeController extends MainController implements IController
{
    /**
     * Dispatch current action
     */
    public function Display(string $callable, $folder, $file)
    {
        $this->folder = $folder;
        $this->file = $file;
    
        return call_user_func(array($this, $callable));
    }

    /**
     * Display home page
     */
    private function Home()
    {
        return View::Get($this->folder, $this->file);
    }

    /**
     * Display contact page
     */
    private function Contact()
    {
        return View::Get($this->folder, $this->file);
    }
}
?>