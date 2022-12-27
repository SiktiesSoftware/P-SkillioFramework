<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/View.php";

class ErrorController extends Controller
{
    /**
     * Display the 403 error page
     * 
     * @return view => View page of error
     */
    public function E403()
    {
        return View::Get($this->folder, $this->file);
    }

    /**
     * Display the 404 error page
     * 
     * @return view => View page of error
     */
    public function E404()
    {
        return View::Get($this->folder, $this->file);
    }
}

?>