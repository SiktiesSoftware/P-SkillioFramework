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
     */
    public static function Get(string $folder, string $file)
    {
        return file_get_contents("pages/views/".$folder."/".$file);
    }
}
    
?>