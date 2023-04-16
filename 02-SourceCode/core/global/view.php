<?php
include_once __DIR__."/../views/View.php";

/**
 * Global view function
 */
function view(string $view, ?array $datas = null) : View
{
    return new View($view, $datas);
}
?>