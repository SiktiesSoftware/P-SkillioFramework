<?php
namespace skillio\core\routing;

use Exception;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\utils\content\Content;
use skillio\core\utils\content\Partial;
use skillio\core\utils\content\View;

/**
 * Class Response
 * Represents an HTTP response in the application.
 *
 * @package skillio\core\routing
 */
class Response
{
    /** @var int The HTTP status code of the response */
    private int $statusCode;

    /** @var string|View|Content The content of the response */
    private string|View|Content $content;

    /** @var array The headers of the response */
    private array $headers;

    /**
     * Get the headers of the response.
     *
     * @return array The headers of the response.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the content of the response.
     *
     * @return View|Content The content of the response.
     */
    public function getContent(): View|Content
    {
        // If the content is not an instance of View or Content, convert it to Content to ensure consistent handling
        if(!$this->content instanceof View && !$this->content instanceof Content)
            $this->content = Content::set($this->content);

        return $this->content;
    }

    /**
     * Set the content of the response.
     *
     * @param string|View|Content|array $content The content to set.
     */
    public function setContent(string|View|Content $content): void
    {
        $this->content = $content;
    }

    /**
     * Add a header to the response.
     * 
     * @param string $name The name of the header.
     * @param string $value The value of the header.
     */
    public function addHeader(string $name, string $value): void
    {
        $this->headers[$name] = $value;
    }

    /**
     * Response constructor.
     *
     * @param string|View|Content $content The content of the response.
     * @param int $statusCode The HTTP status code of the response.
     * @param array $headers The headers of the response.
     */
    public function __construct(string|View|Content $content = "", int $statusCode = 200, array $headers = ["Content-Type" => "text/html"])
    {           
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    /**
     * Display the response content.
     * @param array<Partial> $before Optional content to display before the main content.
     * @param array<Partial> $after Optional content to display after the main content.
     * 
     * @return void
     */
    public function generateResponse(array $before = [], array $after = []): void
    {
        try
        {
            // Set the HTTP status code and headers
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) 
                header("$name: $value");

            if($_ENV["API"] === "true")
                echo $this->content->getContent();
            else
            {
                $data = $this->content->getData();
                $js = $this->content->getJs();
                $css = $this->content->getCss();
                
                // Set the head partial with the title if available
                $head = Partial::set("head", 
                [
                    "title" => $this->content->hasTitle() ? $this->content->getTitle() : null,
                ])->setCss(css: $css);
                
                // Render the response content
                echo $head;
                echo implode("", array_map(fn($partial) => $partial->with(data: $data)->setJs(js: $js), $before));
                echo $this->content;
                echo implode("", array_map(fn($partial) => $partial->with(data: $data)->setJs(js: $js), $after));
            }
        }
        catch (Exception $e)
        {
            new InternalException(
                "Failed to generate response: " . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
?>