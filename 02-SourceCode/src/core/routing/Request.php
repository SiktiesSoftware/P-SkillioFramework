<?php
namespace skillio\core\routing;

use Exception;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\internal\RestrictedAccess;
use skillio\core\internal\RestrictedAccessFactory;
use skillio\core\languages\Lang;
use skillio\core\utils\helpers\ParamCollection;
use skillio\core\routing\Route;
use skillio\core\routing\validation\Validator;

/**
 * Class Request
 * Represents an HTTP request in the application.
 *
 * @package skillio\core\routing
 */
class Request implements RestrictedAccess
{
    /** @var Request|null The singleton instance of the Request class */
    private static Request $instance;

    /** @var Route|null The route associated with the request */
    private ?Route $route;

    /** @var ParamCollection The query parameters of the request */
    private ParamCollection $queryParams;

    /** @var ParamCollection The input parameters of the request */
    private ParamCollection $inputParams;

    /** @var string The HTTP method of the request (GET, POST, etc.) */
    private string $method;

    /** @var string The URL of the request */
    private string $url;

    /** @var array The headers of the request */
    private array $headers;

    /** @var string|null The Bearer token from the Authorization header */
    private ?string $bearerToken;

    /**
     * Get the route associated with the request.
     *
     * @return Route|null The route, or null if not set.
     */
    public function getRoute(): ?Route
    {
        return $this->route;
    }

    /**
     * Get the query parameters of the request.
     *
     * @return ParamCollection The query parameters.
     */
    public function getQueryParams(): ParamCollection
    {
        return $this->queryParams;
    }

    /**
     * Get the input parameters of the request.
     *
     * @return ParamCollection The input parameters.
     */
    public function getInputParams(): ParamCollection
    {
        return $this->inputParams;
    }

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method (GET, POST, etc.).
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the URL of the request.
     *
     * @return string The URL of the request.
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Get the headers of the request.
     *
     * @return array The headers of the request.
     */
    public function headers(): array
    {
        return $this->headers;
    }

    /**
     * Get a specific header from the request.
     *
     * @param string $name The name of the header to retrieve.
     * @return string|null The value of the header, or null if not set.
     */
    public function bearerToken(): ?string
    {
        return $this->bearerToken;
    }

    /**
     * Constructor for Request.
     *
     * @param Route|null $route The route associated with the request.
     * @param ParamCollection $queryParams The query parameters of the request.
     * @param ParamCollection $inputParams The input parameters of the request.
     * @param string $method The HTTP method of the request.
     * @param string $url The URL of the request.
     * @param array $headers The headers of the request.
     * @param string|null $bearerToken The Bearer token from the Authorization header.
     */
    private function __construct(?Route $route, ParamCollection $queryParams, ParamCollection $inputParams, string $method, string $url, array $headers, ?string $bearerToken) 
    {
        $this->route = $route;
        $this->queryParams = $queryParams;
        $this->inputParams = $inputParams;
        $this->method = $method;
        $this->url = $url;
        $this->headers = $headers;
        $this->bearerToken = $bearerToken;
    }

    /**
     * Get the singleton instance of the Request class.
     *
     * @return self|null The instance of the Request class, or null if not set.
     * @throws InternalException If the factory does not allow internal access.
     */
    public static function getInstance(): ?self
    {
        // If the instance is not set, throw an exception
        if (!isset(self::$instance))
            new InternalException("Request instance is not created. Use Request::create() to create a new instance.");
    
        return self::$instance ?? null;
    }

    /**
     * Creates a new Request instance.
     *
     * @param RestrictedAccessFactory $factory The factory to create the request.
     * @return self The created Request instance.
     * @throws InternalException If the factory does not allow internal access.
     */
    public static function create(RestrictedAccessFactory $factory): ?self
    {
        // Validate internal access
        if(!$factory->validateInternalAccess())
            new InternalException(
                "Access denied. The caller class '{$factory->getCaller()}' is not allowed to create Request instances."
            );

        // Create the request instance
        $method = $_SERVER['REQUEST_METHOD'];
        $url = self::getUrl();
        [ "route" => $route, "params" => $integratedQueryParams ] = Router::getRoute(url: $url, method: $method);
        $headers = getallheaders() ?: [];
        $bearerToken = isset($headers['Authorization']) && stripos($headers['Authorization'], 'Bearer ') === 0 ? substr($headers['Authorization'], 7) : null;
        $queryParams = new ParamCollection(params: $_GET);
        $queryParams->merge(params: $integratedQueryParams);
        $inputParams = new ParamCollection(params: $_POST);

        self::$instance = new self(route: $route, queryParams: $queryParams, inputParams: $inputParams, method: $method, url: $url, headers: $headers, bearerToken: $bearerToken);
        return self::$instance;
    }
        

    /**
     * Get the url of the server request uri
     * 
     * @return string => Url of the request uri
     */
    private static function getUrl() : string
    {   
        // Get the url request
        $uri = $_SERVER["REQUEST_URI"];

        // Get the definitive uri of the page
        $url = $uri;
        $position = strpos($uri, '?');
        if ($position !== false) 
            $url = substr($uri, 0, $position);

        return $url;
    }

    /**
     * Check if the request method matches the given method.
     *
     * @param string $method The HTTP method to check against.
     * @return bool True if the request method matches, false otherwise.
     */
    public function isMethod(string $method): bool
    {
        return strcasecmp($this->method, $method) === 0;
    }

    /**
     * Check if the request is for a specific route by name.
     *
     * @param string $name The name of the route to check against.
     * @return bool True if the request is for the specified route, false otherwise.
     */
    public function routeIs(string $name): bool
    {
        return $this->route->getName() === $name;
    }

    /**
     * Validate the request parameters.
     *
     * @param array $fields The fields to validate.
     * @return Validator The validator instance with the validation result.
     */
    public function validate(array $fields): Validator
    {
        // Set the params with query and input params
        $params = new ParamCollection(params: array_merge($this->queryParams->all(), $this->inputParams->all()));
        
        $validator = new Validator(data: $params, fields: $fields)->handle();
        return $validator;
    }

    /**
     * Validate the request parameters and return errors if any.
     *
     * @param array $fields The fields to validate.
     * @return Validator The validator instance with errors if any.
     */
    public function validateWithErrors(array $fields) : Validator
    {
        $params = new ParamCollection(params: array_merge($this->queryParams->all(), $this->inputParams->all()));

        $validator = Validator::getInstance(data: $params, fields: $fields, withErrors: true)->handle();
        return $validator;
    }
}
?>