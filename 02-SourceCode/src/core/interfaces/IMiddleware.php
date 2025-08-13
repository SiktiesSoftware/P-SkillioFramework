<?php
namespace skillio\core\interfaces;

use skillio\core\routing\Request;
use skillio\core\routing\Response;

/**
 * Interface IMiddleware
 *
 * This interface defines the contract for middleware classes in the application.
 * Middleware can process requests and responses, allowing for pre- and post-processing
 * of HTTP requests.
 */
interface IMiddleware
{
    /**
     * Handle the request
     *
     * @param Request $request The request object.
     * @param callable $next The next middleware or controller.
     * @return Response
     */
    public function handle(Request $request, callable $next): Response;
}
?>