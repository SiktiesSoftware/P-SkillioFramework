<?php
namespace skillio\middlewares;

use skillio\core\interfaces\IMiddleware;
use skillio\core\routing\Request;
use skillio\core\routing\Response;

/**
 * ApiMiddleware is a simple middleware that checks if the request method is GET.
 * If the method is not GET, it returns a 405 Method Not Allowed response.
 */
class ApiMiddleware implements IMiddleware
{
    const TOKEN = "jf0qwdjda"; // Example token

    /**
     * Handle the incoming request.
     *
     * @param Request $request The incoming request object.
     * @param callable $next The next middleware or controller to call.
     * @return Response The response object.
     */
    public function handle(Request $request, callable $next): Response
    {
        // Check if token is provided in the Authorization header
        $authHeader = $request->bearerToken();
        if (!$authHeader || $authHeader !== self::TOKEN) {
            // If the token is not provided or does not match, return a 401 Unauthorized response
            return new Response(
                content: " ", 
                statusCode: 401,
                headers: ["Content-Type" => "application/json"]
            );
        }

        // Call the next middleware or controller
        return $next($request);
    }

}