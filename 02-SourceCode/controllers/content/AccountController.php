<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/View.php";

class AccountController extends Controller
{
    /**
     * Display contact page
     * 
     * @return content => content of a page
     */
    public function Connection()
    {
        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE

        // Get the view
        $view = View::Get($this->folder, $this->file);

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the content of the page + variables set
        return $content;
    }

    /**
     * Display contact page
     * 
     * @return content => content of a page
     */
    public function Inscription()
    {
        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE

        // Get the view
        $view = View::Get($this->folder, $this->file);

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the content of the page + variables set
        return $content;
    }
}

?>