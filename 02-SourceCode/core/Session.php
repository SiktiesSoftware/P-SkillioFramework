<?php
/**
 * Manage the session global variable
 */
class Session
{
    /**
     * Set the base session with the config session file
     */
    public static function SetSession()
    {
        // Check if the session global variable is set
        if (is_null($_SESSION) || !isset($_SESSION) || count($_SESSION) == 0) 
        {
            // Get the base session array
            $baseSession = include __DIR__."/../.config/session.config.php";

            // Set the session
            foreach ($baseSession as $key => $value) 
            {
                // Set array position with value
                $_SESSION[$key] = $value;
            }
        }
    }
}

?>