<?php
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
     * @return string => Content of a component
     */
    public static function Set(string $file, array $values) : string
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
     * 
     * @return string => Content of a component
     */
    private static function Get(string $file) : string
    {
        // return the content
        return file_get_contents(__DIR__."/../pages/components/".$file.".component.php");
    }
}

?>