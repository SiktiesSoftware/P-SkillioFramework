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
     * @return api => if the page is an api
     */
    public function Home() : array
    {   
        if (!isset($_GET["lang"])) 
        {
            $_GET["lang"] = "en";
        }

        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE
        $homeTranslations = Lang::GetTranslations("home", $_GET["lang"]);

        // Get the view
        $view = View::Get($this->folder, $this->file);

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the content of the page + variables set
        return ["content" => $content, "api" => false];
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
        return ["content" => $content, "api" => false];
    }
}

?>