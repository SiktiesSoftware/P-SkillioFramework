<?php
namespace skillio\middlewares;

use skillio\core\interfaces\IMiddleware;
use skillio\core\routing\Request;
use skillio\core\routing\Response;

/**
 * AuthMiddleware checks if the user is authenticated before allowing access to certain routes.
 */
class AuthMiddleware implements IMiddleware
{
    /**
     * Handle the incoming request and check for authentication.
     *
     * @param Request $request
     * @param callable $next
     * @return Response
     */
    public function handle(Request $request, callable $next): Response
    {
        // Check if the user is authenticated
        if (!$this->isAuthenticated($request)) {
            return new Response(
                content: '401 Unauthorized',
                statusCode: 401
            );
        }

        // Call the next middleware or controller
        return $next($request);
    }

    /**
     * Check if the user is authenticated.
     *
     * @param Request $request
     * @return bool
     */
    private function isAuthenticated(Request $request): bool
    {
        // Implement your authentication logic here
        return false;
    }
}