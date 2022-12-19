<?php

namespace app\core;

class Request
{
    public function GetUrl()
    {
        $url = $_SERVER["REQUEST_URI"];
        
    }
}

?>