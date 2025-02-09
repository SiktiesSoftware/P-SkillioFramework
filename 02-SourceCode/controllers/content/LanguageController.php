<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/languages/Lang.php";

/**
 * Manage home pages
 */
class LanguageController extends Controller
{
    /**
     * Display home page
     * 
     * @return content => content of a page
     * @return api => if the page is an api
     */
    public function GetLanguage() : array
    {   
        if (!isset($_GET["lang"]) || !isset($_GET["page"]))
        {
            return ["content" => json_encode(["error" => "Language or page not set"]), "api" => true];
        }

        $translations = Lang::GetTranslations($_GET["page"], $_GET["lang"]);

        // Return the content of the page + variables set
        return ["content" => json_encode($translations), "api" => true];
    }
}

?>