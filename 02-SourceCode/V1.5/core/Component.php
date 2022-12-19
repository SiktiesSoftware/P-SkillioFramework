<?php

namespace app\core;

/**
 * Manage the components
 */
class Component
{
    /**
     * Get a component
     * 
     * @param file => file of the component
     * @param values => values to send to the component
     * 
     * @return content => Content of a component
     */
    public static function Set(string $file, array $values)
    {
        // Set the values
        $componentValues = $values;

        // Get the file content
        $component = Component::Get($file);
        
        // Replace variables
        ob_start();
        eval('?>' . $component);
        $content = ob_get_clean();

        // Return the content of the component
        return $content;
    }

    /**
     * Get content of the file component
     * 
     * @param file => Name of the file
     */
    public static function Get(string $file)
    {
        return file_get_contents(__DIR__."/../pages/components/".$file);
    }
}

?>