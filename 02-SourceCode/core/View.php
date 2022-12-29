<?php
/**
 * Manage the views
 */
class View
{
    /**
     * Get a view
     * 
     * @param folder => folder of the view
     * @param file => file of the view
     * 
     * @return string => Content of a page
     */
    public static function Get(string $folder, string $file) : string
    {
        // Return the content of a view
        return file_get_contents(__DIR__."/../pages/views/".$folder."/".$file);
    }
}

?>