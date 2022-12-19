<?php

namespace app\core;

class Controller
{
    private string $folder, $file;      // Folder and file of the page to display

    /**
     * Execute a function
     * 
     * @param callable => Callable function in the class
     * @param folder => Folder of the view
     * @param file => View file
     * 
     * @return function => return the content of a function
     */
    public function Display(string $callable, $folder, $file, $controller)
    {
        $this->folder = $folder;
        $this->file = $file;

        return call_user_func(array($controller, $callable));
    }
}

?>