<?php
/**
 * Manage the views
 */
class View
{
    /**
     * View constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Get a view
     * 
     * @param folder => folder of the view
     * @param file => file of the view
     * 
     * @return pageContent => Content of a page
     */
    public static function Get(string $folder, string $file)
    {
        return file_get_contents(__DIR__."/../views/".$folder."/".$file);
    }
}
    
?>