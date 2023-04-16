<?php
include_once __DIR__."/../routing/Router.php";
include_once __DIR__."/../routing/Route.php";

/**
 * Global view function
 * 
 * @param routeName => Name of the route
 * 
 * @return string => Return the route link
 */
function route(string $routeName) : string
{
    return Router::GetRouteByName($routeName)->link;
}

/**
 * Get a route object
 * 
 * @param routeName => Name of the route
 * 
 * @return Route => Return the route object
 */
function getRoute(string $routeName) : Route
{
    return Router::GetRouteByName($routeName);
}
?>