<?php
class Auth
{
    public function IsConnected() : bool
    {
        return true;
    }

    public function HasPermission() : bool
    {
        return false;
    }
}
?>