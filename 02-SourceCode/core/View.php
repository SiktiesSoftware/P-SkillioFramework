<?php
/**
 * Manage the views
 */
class View
{
    private static array $instances = [];
    private string $name;
    private array $datas = [];

    /**
     * Get an instance of the view
     * 
     * @return View => return the view
     */
    private function __construct(string $name)
    {
        // Set the name of the view
        $this->name = $name;
    }

    /**
     * Get an instance of the view
     * 
     * @param name => name of the view
     * 
     * @return View => return the view
     */
    public static function GetByName(string $name) : View
    {
        // Get the view
        if (!isset(self::$instances[$name]))
            self::$instances[$name] = new View($name);
        
        // Reset datas
        self::$instances[$name]->datas = [];

        // Return the view
        return self::$instances[$name];
    }

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

    /**
     * Get the content of a view
     * 
     * @return string => return the content of a view
     */
    private function Find() : ?string
    {
        $directory = __DIR__ . '/../pages/';
        $fileName = $this->name . '.view.php';
        $filePath = $this->FindFileRecursively($directory, $fileName);

        // Return the content of the view
        if ($filePath) 
            return file_get_contents($filePath);
        
        header("location: ". Route::GetRouteByName("404")->GetLink());
    }
    
    /**
     * Find a file recursively
     * 
     * @param directory => directory to search
     * @param fileName => name of the file to find
     * 
     * @return string => return the path of the file
     */
    private function FindFileRecursively(string $directory, string $fileName) : ?string
    {   
        // Find the file recursively
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        // Find the file
        foreach ($iterator as $file) 
            if ($file->isFile() && strcasecmp($file->getFilename(), $fileName) === 0) 
                return $file->getPathname();

        // Return null if the file is not found
        return null;
    }

    /**
     * Parse the datas and display the view
     * 
     * @return string => return the view content
     */
    public function Parse() : string
    {
        // Get the view
        $view = $this->Find();

        // Set the datas
        foreach ($this->datas as $key => $value) 
            $$key = $value;

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the view content
        return $content;
    }

    /**
     * Add datas to the view
     * 
     * @param datas => datas to add
     * 
     * @return View => return the view
     */
    public function AddDatas(array $datas) : self
    {
        // Add datas to the view
        $this->datas = $datas;

        // Return the view
        return $this;
    }
}

?>