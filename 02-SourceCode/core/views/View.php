<?php
/**
 * Manage the views
 */
class View
{
    public string $view;                                        // Name of the view
    protected array $datas;                                     // Datas sended to the view
    private const PATH = __DIR__."/../../pages/views";          // Path of the views
    private const EXTENSION = ".php";                           // Path of the views

    /**
     * View class constructor
     * 
     * @param path => Path of the view
     */
    public function __construct(string $view, ?array $datas = null)
    {
        $this->view = $view;
    }

    /**
     * Set datas to the view
     * 
     * @param datas => Array of datas
     */
    public function WithDatas(array $datas)
    {
        $this->datas = $datas;
        return $this;
    }

    /**
     * Parse the datas and display the view
     * 
     * @return string => return the view content
     */
    public function Parse() : string
    {
        // Get the view
        $view = $this->Get();

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the view content
        return $content;
    }

    /**
     * Get a view file
     * 
     * @return string => Content of a page
     */
    private function Get() : string
    {
        // Set the path of the file
        $path = null;
        foreach (explode("->", $this->view) as $key => $file) 
        {
            $path .= "/".$file;
        }
        
        // Return the content of a view
        return file_get_contents(self::PATH . $path . self::EXTENSION);
    }
}
?>