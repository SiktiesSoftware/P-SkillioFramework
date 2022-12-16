<?php
/************************************
 * Project       :
 * Place         : Lausanne - Switzerland                      
 * Author        : Damien Loup      
 * Description                               
 *    - Class    : Manage the database input and output              
 ************************************/

include_once "controllers/MainController.php";
include_once "controllers/content/IController.php";

/**
 * Class alowing to access the database
 */
class ErrorController extends MainController implements IController
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

    private function E404()
    {
        return View::Get($this->folder, $this->file);
    }
}
?>