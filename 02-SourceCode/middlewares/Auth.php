<?php
/**
 * Manage the verifications of the authentification when called
 */
class Auth
{
    /**
     * Check if the user is connected
     * 
     * @return array => Array of redirection
     */
    public static function IsConnected() : array
    {
        // Return the access
        return ["hasAccess" => true];        
        //return ["hasAccess" => false, "Request" => Route::GetRouteByName("404")];
    }

    /**
     * Check if the user has the permission to access this resource
     * 
     * @return array => Array of redirection
     */
    public static function HasPermission() : array
    {
        /** Get the role of the user */
        //$userRole = "admin";
        $userRole = "admin";

        // Check if the datas aren't null
        if(!is_null(Middleware::$datas))
        {
            // Get the role from the datas for this function
            $roles = Middleware::$datas["Roles"];

            // Get each role
            foreach ($roles as $role) 
            {
                // Check if the actual role is the same as the user
                if ($userRole != $role) continue;

                // Return the good access
                return ["hasAccess" => true];
            }
        }   

        // Return the error
        return ["hasAccess" => false, "Request" => Route::GetRouteByName("403")];
    }
}
?>