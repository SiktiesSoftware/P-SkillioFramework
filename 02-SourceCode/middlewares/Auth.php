<?php
class Auth
{
    /**
     * Check if the user is connected
     * 
     * @return array => Array of redirection
     */
    public static function IsConnected() : array
    {
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

        // Check if the datas aren't null
        if(!is_null(Middleware::$datas))
        {
            
        }   

        //return ["hasAccess" => true];
        return ["hasAccess" => false, "Request" => Route::GetRouteByName("403")];
    }
}
?>