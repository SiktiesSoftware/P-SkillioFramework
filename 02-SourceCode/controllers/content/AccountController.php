<?php
include_once __DIR__."/../../core/Controller.php";
include_once __DIR__."/../../core/form/DataRequest.php";
include_once __DIR__."/../../core/form/Verifications.php";
include_once __DIR__."/../../core/View.php";

/**
 * Manage account pages
 */
class AccountController extends Controller
{
    const NICKNAME_EMPTY_ERROR = "The nickname field is empty";
    const PASSWORD_EMPTY_ERROR = "The password field is empty";
    const INCORRECT_LOGIN = "The password or the nickname is false";

    /**
     * Display the connection page
     * 
     * @return content => content of a page
     */
    public function Connection()
    {
        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE

        // Get the view
        $view = View::Get($this->folder, $this->file);
        
        // Set errors
        $errors = null;
        if (isset($_SESSION["errors"])) 
        {
            foreach ($_SESSION["errors"] as $key => $value) 
            {
                $errors[$key] = $value;
            }
        }

        // Replace variables
        ob_start();
        eval('?>' . $view);
        $content = ob_get_clean();

        // Unset errors
        unset($_SESSION["errors"]);

        // Return the content of the page + variables set
        return $content;
    }

    /**
     * Disply the inscription page
     * 
     * @return content => content of a page
     */
    public function Inscription()
    {
        // Return the content of a page ===> INCLUDE THE COMPONENTS INTO THE PAGE

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
     * Verify the $_POST for the login
     */
    public function VerifyLogin()
    {
        // Set variable
        $isValid = true;

        // Check if fields are filled
        if ($_POST["nickname"] == "") 
        {
            $_SESSION["errors"]["nickname"] = self::NICKNAME_EMPTY_ERROR;
            $isValid = false;
        }
        if ($_POST["password"] == "") 
        {
            $_SESSION["errors"]["password"] = self::PASSWORD_EMPTY_ERROR;
            $isValid = false;
        }

        // Get all users
        $usersArray = t_user::GetAll();

        // Set array of users
        $users = array();

        // Browse array to set objects of users
        foreach ($usersArray as $key => $actualUser) 
        {
            $user = new User($actualUser["idUser"], $actualUser["nickname"], $actualUser["password"], $actualUser["role"]);
            $users[] = $user;
        }

        // Check if user is found
        $isUserFound = false;
        // Get all the users
        foreach ($users as $key => $user) 
        {
            // Check if the nickname is the same as the sended
            if ($user->nickname == $_POST["nickname"])
            {
                // Set user found to true
                $isUserFound = true;

                // Check if the password is the same as sended
                if (password_verify($_POST["password"], $user->password)) 
                {
                    $connectedUser = t_user::GetUserByIdUser($user->id);
                    $_SESSION["user"] = $connectedUser;
                }
                else
                {
                    $_SESSION["errors"]["all"] = self::INCORRECT_LOGIN;
                    $isValid = false;
                }
            }
        }

        // Check if user is found
        if (!$isUserFound) 
        {
            $_SESSION["errors"]["all"] = self::INCORRECT_LOGIN;
            $isValid = false;
        }

        // Check if the verification was valid
        if (!$isValid) 
        {
            header("location: ". Route::GetGroupByName("Account")->GetRoute("connection")->link);
        }
        else
        {
            header("location: ". Route::GetRouteByName("home")->link);
        }
    }

    /**
     * Verify the informations from the $_POST for the inscriptions
     */
    public function VerifySignIn(DataRequest $request)
    {
        // Set the verifications object with the request
        $verifications = new Verifications($request);

        // Set the validations
        $request->Validate([
            "login" => 
            [
                $verifications->Required(),
                $verifications->Max(255),
                $verifications->Date()->After(new DateTime("12/31/2019"))
            ]
        ]);
    }

    /**
     * Disconnect the user
     */
    public function Disconnect()
    {
        unset($_SESSION["user"]);
        header("location: ".Route::GetRouteByName("home")->link);
    }
}

?>