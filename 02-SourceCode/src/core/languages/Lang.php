<?php
namespace skillio\core\languages;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\utils\helpers\ParamCollection;

/**
 * Lang class provides methods to manage and retrieve language translations.
 * It loads language files from the resources/langs directory and allows access to translations.
 */
class Lang
{
    /** @var string The base path for language files */
    private const BASE_PATH = __DIR__ . "/../../resources/langs/";

    /** @var string The parameter name for language in the URL */
    public const PARAM_NAME = "{lang}";

    /** @var array $languages Array to hold language data */
    private static array $languages = [];

    /**
     * Check if integrated languages feature is active.
     * @return bool Returns true if integrated languages are active, false otherwise
     */
    public static function isIntegratedLanguagesActive(): bool
    {
        return $_ENV["INTEGRATED_LANGUAGES"] === "true";
    }

    /**
     * Get the default language set in the environment variables.
     * @return string The default language code
     */
    public static function getDefaultLanguage(): string
    {
        return $_ENV["DEFAULT_LANGUAGE"];
    }

    /**
     * Get the base path for language files
     * @return string Base path for language files
     */
    public static function setup(): void
    {
        // Walk through the langs folder to find what languages are available
        $langs = scandir(self::BASE_PATH);

        // Keep only the the directories
        $langs = array_filter($langs, fn ($lang) => $lang !== "." && $lang !== ".." && is_dir(self::BASE_PATH . $lang));

        // Load each language file
        foreach ($langs as $lang) 
        {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(self::BASE_PATH . $lang)
            );
            
            foreach ($files as $file) 
            {
                if ($file->isFile() && $file->getExtension() === 'php') 
                {
                    $langData = require $file->getPathname();
                    if (is_array($langData)) 
                        self::$languages[$lang][$file->getBasename('.lang.php')] = $langData;
                }
            }
        }
    }

    /**
     * Get the language data for a specific language or key
     * @param string $lang Language code
     * @param string|null $key Optional key to get specific data
     * @return ParamCollection Returns an instance of ParamCollection with the requested data
     */
    public static function getLanguage(string $lang) : ParamCollection
    {
        if(!isset(self::$languages[$lang])) 
            new InternalException(message: "Language '$lang' not found.", code: 404);

        return new ParamCollection(self::$languages[$lang]);
    }

    /**
     * Get the list of available languages
     * @return array Returns an array of available languages
     */
    public static function getAvailableLanguages(): array
    {
        return array_keys(self::$languages);
    }
}
?>