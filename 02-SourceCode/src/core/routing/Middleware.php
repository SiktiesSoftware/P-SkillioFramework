<?php
namespace skillio\core\routing;

use InternalIterator;

/**
 * Middleware class that manages a group of routes and their associated middlewares.
 */
class Middleware
{
    /** @var Route[] The routes associated with the group. */
    private array $routes;

    private function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }
    
    /**
     * Creates a new Middleware instance with the provided middlewares and routes.
     *
     * @param array $middlewares An array of middleware instances.
     * @param Route[] $routes An array of Route instances.
     * @return self
     * @throws InternalIterator If any route is not an instance of Route.
     */
    public static function create(array $middlewares, array $routes = []): self
    {
        // Validate that all provided routes are instances of Route
        foreach ($routes as $route) {
            if (!$route instanceof Route) 
                new InternalIterator(
                    'All routes must be instances of Route.'
                );
            $route->setMiddlewares($middlewares);
        }

        return new self($routes);
    }

    /**
     * Creates a group of routes with the specified controller.
     *
     * @param mixed $controller The controller to associate with the group.
     * @return Group The created group instance.
     */
    public function group(mixed $controller): Group
    {
        return Group::create($controller, $this->routes);
    }
}
?>