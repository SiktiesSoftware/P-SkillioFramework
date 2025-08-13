<?php
namespace skillio\core\routing;

use skillio\controllers\MainController;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\internal\RestrictedAccessFactory;
use skillio\core\languages\Lang;

/**
 * Router class to handle routing logic in the application.
 */
class Router
{
    /** @var array<string,Route> $routes */
    private static array $routes = [];

    /**
     * Check if the URL contains parameters and matches the route.
     * @param string $url The URL to check.
     * @param Route $route The route to check against.
     * @return bool True if the URL matches the route, false otherwise.
     */
    private static function checkForParameters(string $url, Route $route, array &$queryParams, bool $useBaseUrl = false, bool $verifyEntireString = true) : bool
    {
        $pattern = "#^" . preg_replace("/{[\w]+}/", "([\w-]+)", $route->getUrl()) . "#";
        if(!preg_match($pattern, $url, $params))
            return false;

        array_shift($params); // Remove the full match
        preg_match_all('/{([\w]+)}/', $route->getUrl(), $paramNames);

        // replace the params with the param names
        $parsedUrl = "";
        if (count($params) === count($paramNames[0]))
            $parsedUrl = str_replace($paramNames[0], $params, $route->getUrl());

        $queryParams[$route->getName()] = array_combine($paramNames[1], $params);

        // If the URL matches the pattern, return true
        if((!empty($parsedUrl) && $parsedUrl === $url) || (str_contains($parsedUrl, '/*') && str_starts_with($url, rtrim($parsedUrl, '/*'))))
            return true;

        return false;
    }

    /**
     * Check if the URL starts with the base pattern of the route.
     * @param string $url The URL to check.
     * @param Route $route The route to check against.
     * @return bool True if the URL starts with the base pattern, false otherwise.
     */
    private static function checkForWildcard(string $url, Route $route, ?array $queryParams = null) : bool
    {
        // Check if the URL starts with the base pattern of the route
        // $basePattern = str_replace('/*', '/', $route->getUrl());
        $basePattern = $route->getUrl();

        // Replace the params in the base pattern {lang} => $queryParams['lang']
        if (!empty($queryParams[$route->getName()]) && strpos($basePattern, '{') !== false) 
            $basePattern = preg_replace_callback(
                pattern: '/{([\w]+)}/',
                callback: fn($matches) => $queryParams[$route->getName()][$matches[1]],
                subject: $basePattern
            );
            
        // Check if the URL starts with the base pattern and has other characters after it like Home => /en/ and 404 => /en/zrzzr 
        if((str_contains($basePattern, '/*') && strlen($url) > strlen($basePattern) - 1) || $url === $basePattern)
            return true;

        return false;
    }

    /**
     * Get the route for a given URL and method.
     *
     * @param string $url The URL to match.
     * @param string $method The HTTP method (e.g., GET, POST).
     * @return array<int,Route|array>|null The matched route or null if no match found. Returns [Route, $params] if found.
     */
    public static function getRoute(string $url, string $method) : ?array
    {
        $queryParams = [];
        
        // Filter routes based on URL and method
        $routes = array_filter(self::$routes, function (Route $route) use ($url, $method, &$queryParams) 
        {
            // If the route does not match the method, skip it
            if (!in_array($method, $route->getMethods())) 
                return false;

            // First check for exact match
            if ($route->getUrl() === $url) 
                return true;
            // Check for mixed wildcard and parameterized matches
            else if (strpos($route->getUrl(), "{") || str_ends_with($route->getUrl(), '/*')) 
                return self::checkForParameters(url: $url, route: $route, queryParams: $queryParams, useBaseUrl: true, verifyEntireString: false) && self::checkForWildcard(url: $url, route: $route, queryParams: $queryParams);

            return false;
        });

        // Sort the routes by their specificity url : /en/users/base => stays /{lang}/users/base and /{lang}/users/{id} The most specific is /{lang}/users/base 
        // This is done by checking slash by slash getting points if the part is exactly the same
        $routes = array_map(function(Route $route) use ($url) 
        {
            $routeUrlParts = explode('/', $route->getUrl());
            array_shift($routeUrlParts); // Remove the first empty part if exists

            $urlParts = explode('/', $url);
            array_shift($urlParts); // Remove the first empty part if exists

            // If the number of parts does not match add empty parts to the route URL to check if the url is a wildcard
            if(count($routeUrlParts) < count($urlParts))
                $routeUrlParts = array_pad($routeUrlParts, count($urlParts), '*');
            elseif(count($routeUrlParts) > count($urlParts))
                $urlParts = array_pad($urlParts, count($routeUrlParts), '**');
            
            $points = 0;
            foreach (array_combine($routeUrlParts, $urlParts) as $routePart => $urlPart) 
                if ($routePart === $urlPart) 
                    $points += 2;
                elseif($routePart === '*') 
                    $points += 1; // Wildcard matches any part, so give it half a point
            
            return [
                "route" => $route,
                "points" => $points,
            ];
        }, $routes);

        // Get the route with the most points
        if (!empty($routes)) 
        {   
            // Sort by points in descending order (highest points first)
            usort($routes, function($a, $b) {
                return $b['points'] - $a['points'];
            });
            
            $finalRoute = reset($routes)["route"]; // Return the most precise route
            
            // If the final route has a name, return it with the query parameters
            if ($finalRoute->hasName())
                return ["route" => $finalRoute, "params" => $queryParams[$finalRoute->getName()] ?? []];

            // If no name is found, throw an exception
            new InternalException(
                "Route without a name found. This should not happen in a well-defined routing system."
            );
        }

        return null;
    }

    /**
     * Get a route by its name.
     *
     * @param string $name The name of the route.
     * @return Route|null The matched route or null if no match found.
     */
    public static function getRouteByName(string $name) : ?Route
    {
        // Filter routes by name
        $filteredRoutes = array_filter(self::$routes, function (Route $route) use ($name) 
        {
            return $route->hasName() && $route->getName() === $name;
        });

        if (count($filteredRoutes) > 0) 
            return array_values($filteredRoutes)[0];
        return null;
    }

    /**
     * Check if the URL is a static asset that should be ignored
     */
    public static function isStaticAsset(string $url): bool
    {
        $staticExtensions = ['.ico', '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.webp', '.woff', '.woff2', '.ttf', ".json"];
        
        foreach ($staticExtensions as $extension) 
            if (str_ends_with($url, $extension))
                return true;
        
        // Also check for common static paths
        $staticPaths = ['/favicon.ico', '/robots.txt', '/sitemap.xml'];
        return in_array($url, $staticPaths);
    }

    /**
     * Route the request to the appropriate controller and method.
     */
    public static function route()
    {            
        // If the URL is a static asset, do not route it
        if (self::isStaticAsset(url: $_SERVER['REQUEST_URI']))
            return null;

        // Get the request
        $request = Request::create(factory: new RestrictedAccessFactory());
        
        // If the request URL does not end with a slash, redirect to the same URL with a slash
        if(!str_ends_with($request->url(), "/") && count(explode("/", $request->url())) < 3)
            self::redirect(url: $request->url() . "/");
        
        $route = $request->getRoute();
        $doesContainAvailableLangParam = array_any(Lang::getAvailableLanguages(), fn($lang) => str_starts_with($request->url(), "/$lang/"));
        if(Lang::isIntegratedLanguagesActive() && !$doesContainAvailableLangParam)
        {
            // If the route is not found, try to find an alternative route with the default language
            $altRoute = Router::getRoute(url: "/en". $request->url(), method: $request->method())["route"] ?? null;
            $isFillRoute = fn($r) => $r ? str_contains($r->getUrl(), "/*") : false;

            // If the route is not found and the URL does not contain a language parameter, redirect to the default language
            if($isFillRoute($route) && $altRoute)
                $route = $altRoute;
            
            self::redirect(url: $route && !$isFillRoute($route) ? $route->link(["lang" => Lang::getDefaultLanguage()]) : self::replaceFirstPartOfUrl(url: $request->url(), replacement: Lang::getDefaultLanguage()));
        }
        
        // Dispatch the request to the MainController
        MainController::dispatch(request: $request);
    }

    /**
     * Replace the first part of the URL with a given replacement.
     *
     * @param string $url The original URL.
     * @param string $replacement The replacement string.
     * @return string The modified URL.
     */
    private static function replaceFirstPartOfUrl(string $url, string $replacement): string
    {
        // Replace the first part of the URL with the replacement
        $parts = explode('/', $url);
        array_shift($parts);
        
        $newParts[0] = $replacement;

        $parts = array_merge($newParts, $parts);
        return "/" . implode('/', $parts);
    }

    /**
     * Redirect to a given URL.
     *
     * @param string $url The URL to redirect to.
     */
    private static function redirect(string $url): void
    {
        header("Location: $url", true, 302);
        exit();
    }

    /**
     * Add a new route to the router.
     *
     * @param Route $route The route to add.
     */
    public static function addRoute(Route $route): void
    {
        self::$routes[] = $route;
    }
}
?>