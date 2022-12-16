<?php
include_once __DIR__."/../MainController.php";
include_once __DIR__."/IController.php";

include_once __DIR__."/../../models/tables/t_user.php";
include_once __DIR__."/../../models/repositories/User.php";

/**
 * Class which manage the link between user pages and database
 */
class UserController extends MainController implements IController
{
    /**
     * Dispatch current action
     * 
     * @param callable => Callable function in the class
     * @param folder => Folder of the view
     * @param file => View file
     * 
     * @return function => return the content of a function
     */
    public function Display(string $callable, $folder, $file)
    {
        $this->folder = $folder;
        $this->file = $file;
    
        return call_user_func(array($this, $callable));
    }

    private function Users()
    {
        // Get all users
        $usersArray = t_user::GetAllUsers();

        // Set array of users
        $users = array();

        // Browse array to set objects of users
        foreach ($usersArray as $key => $actualUser) 
        {
            $user = new User($actualUser["idUser"], $actualUser["nickname"], $actualUser["password"]);
            $users[] = $user;
        }

        // Get the view
        $view = View::Get($this->folder, $this->file);

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Return the content of the page + variables set
        return $content;
    }

    /**
     * Display page User with the user selected by ID
     * 
     * @return content => Content of the page User.php with the variables set
     */
    private function User()
    {
        // Get the user by id
        $userArray = t_user::GetUserByIdUser($_GET["id"]);
        var_dump($userArray);
        $user = new User($userArray["idUser"], $userArray["nickname"], $userArray["password"]);

        // Get the view
        $view = View::Get($this->folder, $this->file);

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();
        
        // Return the content of the page + variables set
        return $content;
    }
}
?>