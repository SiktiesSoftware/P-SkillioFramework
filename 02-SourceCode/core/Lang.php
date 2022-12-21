<?php
/**
 * Manage the languages of the website
 */
class Lang
{
    public static $language = "fr";

    /**
     * Get the actual website language
     * 
     * @return Lang => actual language
     */
    public static function GetActualLanguage()
    {
        return Lang::$language;
    }

    /**
     * Set the actual website language
     * 
     * @param lang => Lang sended
     */
    public static function SetActualLanguage($lang)
    {
        Lang::$language = $lang;
    }

    /**
     * Get the translations
     * 
     * @param filename => name of the file of translations
     * 
     * @return array => array of the translations
     */
    public static function GetTranslations($fileName) : array
    {
        $translations = include __DIR__."/../resources/langs/".Lang::$language."/".$fileName.".lang.php";
        return $translations;
    }
}

?>