<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/View.php";

class ErrorController extends Controller
{
    public function E404()
    {
        return View::Get($this->folder, $this->file);
    }
}

?>