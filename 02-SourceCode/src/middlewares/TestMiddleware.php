<?php
namespace skillio\middlewares;

use skillio\core\interfaces\IMiddleware;
use skillio\core\routing\Request;
use skillio\core\routing\Response;

/**
 * TestMiddleware is a simple middleware that checks if the request method is GET.
 * If the method is not GET, it returns a 405 Method Not Allowed response.
 */
class TestMiddleware implements IMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request The incoming request object.
     * @param callable $next The next middleware or controller to call.
     * @return Response The response object.
     */
    public function handle(Request $request, callable $next): Response
    {
        if ($request->method() !== 'GET') {
            // If the request method is not GET, return a 405 Method Not Allowed response
            return new Response(
                content: '405 Method Not Allowed', 
                statusCode: 405
            );
        }

        // Call the next middleware or controller
        return $next($request);
    }

}