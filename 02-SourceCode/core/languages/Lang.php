<?php
/**
 * Manage the languages of the website
 */
class Lang
{
    public static array $languages = ["fr", "en"];

    /**
     * Get the translations
     * 
     * @param filename => name of the file of translations
     * 
     * @return array => array of the translations
     */
    public static function GetTranslations($fileName) : array
    {
        $translations = include __DIR__."/../../resources/langs/".$_SESSION["lang"]."/".$fileName.".lang.php";
        return $translations;
    }
}

?>