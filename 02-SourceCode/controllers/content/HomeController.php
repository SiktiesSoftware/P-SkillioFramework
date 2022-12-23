<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/languages/Lang.php";

/**
 * Manage home pages
 */
class HomeController extends Controller
{
    /**
     * Display home page
     * 
     * @return content => content of a page
     */
    public function Home()
    {
        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE
        $homeTranslations = Lang::GetTranslations("home");

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
    public function Contact()
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