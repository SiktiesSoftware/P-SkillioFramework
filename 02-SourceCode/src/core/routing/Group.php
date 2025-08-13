<?php
namespace skillio\core\routing;

use skillio\core\internal\exceptions\InternalException;
use skillio\core\languages\Lang;

/**
 * Class Group
 * Represents a group of routes that can share a common controller and URL prefix.
 */
class Group
{
    /** @var Route[] The routes associated with the group. */
    private array $routes = [];

    /**
     * Group constructor.
     *
     * @param Route[] $routes The routes to be included in the group.
     * @throws InternalException If the provided routes are not instances of Route.
     */
    private function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Create a new Group instance.
     *
     * @param Controller $controller The controller associated with the group.
     * @param Route[] $routes The routes to be included in the group.
     * @return self A new instance of the Group class.
     */
    public static function create(mixed $controller, array $routes): self
    {
        foreach ($routes as $route) 
        {
            if (!$route instanceof Route) 
                new InternalException(
                    "Invalid route provided. Expected instance of Route, got " . gettype($route)
                );
            $route->setController($controller);
        }

        return new self($routes);
    }

    public function prefix(string $prefix): self
    {
        // Set the prefix for the group
        foreach ($this->routes as $route) 
        {
            $routeUrl = $route->getUrl();
                
            $position = strpos($routeUrl, Lang::PARAM_NAME) + strlen(Lang::PARAM_NAME);
            $routeUrl = Lang::isIntegratedLanguagesActive() 
            ? substr($routeUrl, 0, $position) . $prefix . substr($routeUrl, $position) 
            : $prefix . $routeUrl;

            $route->setUrl($routeUrl);
        }
        
        return $this;
    }
}
?>