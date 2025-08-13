<?php
namespace skillio\controllers;

use skillio\core\internal\exceptions\InternalException;
use skillio\core\languages\Lang;
use skillio\core\routing\Request;
use skillio\core\routing\Response;
use skillio\core\utils\content\Partial;
use skillio\core\utils\content\View;

class MainController
{
    /**
     * Handles the incoming request and returns a response.
     *
     * @param Request $request The incoming request.
     * @return Response The response to be sent back to the client.
     */
    public static function dispatch(Request $request)
    {
        // Get the route
        $route = $request->getRoute();
        
        $response = null;
        if ($route)
            $response = $route->getHandler()($request);
        else
            new InternalException(
                "No route found for the request: " . $request->url(),
            );
        
        // If the response is not an instance of Response, throw an exception
        if(!$response instanceof Response)
        {
            // If we are in debug mode, throw an exception
            if ($_ENV["DEBUG"] === "true")
                new InternalException(
                    "The route handler must return an instance of Response, got: " . gettype($response),
                );
        
            // If we are not in debug mode, return a 500 error response
            $response = new Response(
                    content: View::set(name: "errors.500"),
                    statusCode: 500,
                    headers: ["Content-Type" => "text/html"]
                );
        }

        self::display(response: $response);
    }

    /**
     * Returns global data for the view.
     * This method needs to be modified to include the necessary data for the view.
     * It is also automatically used when getting the response from the route.
     *
     * @param Request $request The request object.
     * @return array The global data for the view.
     */
    public static function globalData(Request $request) : array
    {
        // Set global data for the view
        $lang = $request->getQueryParams()->get(key: "lang", default: "en");
        $language = Lang::getLanguage(lang: $lang);

        $langsTranslations = $language->get(key: "langs");

        return [
            "langsTranslations" => $langsTranslations,
        ];
    }

    /**
     * Displays the response content.
     *
     * @param Response $response The response to be displayed.
     */
    private static function display(Response $response)
    {
        // If the response is not an error response, generate the response with header and footer
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) 
        {
            $response->generateResponse(
                [Partial::set(name: "header")], 
                [Partial::set(name: "footer")]
            );
        }
        else
            $response->generateResponse();
    }
}
?>