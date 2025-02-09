<?php
/**
 * Manage the languages of the website
 */
class Lang
{
    public static array $languages = [];  // Languages of the website
    
    /**
     * Get the translations
     * 
     * @param filename => name of the file of translations
     * @param language => language of the translations
     * 
     * @return array => array of the translations
     */
    public static function GetTranslations(string $fileName, string $language) : array
    {
        // Check if the file exists
        if (!file_exists(__DIR__."/../../resources/langs/".$language."/".$fileName.".lang.php"))
            return [];

        // Get the translations
        $translations = include __DIR__."/../../resources/langs/".$language."/".$fileName.".lang.php";
        return $translations;
    }

    /**
     * Setup the language
     */
    public static function Setup() : void
    {
        // Walk through the langs folder to find what languages are available
        $langs = scandir(__DIR__."/../../resources/langs/");

        // Walk through the langs
        foreach ($langs as $lang)
        {
            // Check if the lang is a directory
            if (is_dir(__DIR__."/../../resources/langs/".$lang) && $lang != "." && $lang != "..")
            {
                // Register the lang
                if (!in_array($lang, Lang::$languages))
                    Lang::$languages[] = $lang;
            }
        }
    }
}

?>