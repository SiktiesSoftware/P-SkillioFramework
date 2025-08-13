<?php
namespace skillio\core\utils;

use skillio\core\interfaces\INestable;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\templateEngine\TemplateEngine;
use skillio\core\utils\content\Component;
use skillio\core\utils\content\View;

/**
 * Abstract class Renderable
 * This class serves as a base for all renderable components in the application.
 * It provides methods to handle rendering and data management.
 */
abstract class Renderable
{   
    /** @var string */
    protected string $path;

    /** @var array<string,mixed> */
    protected array $data;

    /** @var string */
    protected string $content;

    /** @var array|null */
    protected ?array $css = [];

    /** @var array|null */
    protected ?array $js = [];

    /**
     * Get the path of the renderable.
     *
     * @return string The path to the renderable file.
     */
    public function getPath(): string
    {
        return $this->path;
    }   

    /**
     * Get the content of the renderable.
     *
     * @return string The content of the renderable.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content of the renderable.
     *
     * @param string $content The content to set.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    
    /**
     * Get the data for the renderable.
     * 
     * @return array The data associated with the renderable.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Gets the CSS files associated with the view.
     * 
     * @return array The array of CSS files.
     */
    public function getCss(): array
    {
        return $this->css ?? [];
    }

    /**
     * Sets the CSS files for the view.
     *
     * @param array $css The array of CSS files to set.
     */
    public function setCss(array $css): self
    {
        $this->css = $css;
        return $this;
    }

    /**
     * Gets the JS files associated with the view.
     *
     * @return array The array of JS files.
     */
    public function getJs(): array
    {
        return $this->js ?? [];
    }

    /**
     * Sets the JS files for the view.
     *
     * @param array $js The array of JS files to set.
     */
    public function setJs(array $js): self
    {
        $this->js = $js;
        return $this;
    }

    /**
     * Returns the base path for the renderable.
     *
     * @return string
     */
    abstract protected static function getBasePath(): string;

    /**
     * Converts the renderable to a string.
     *
     * @return string The rendered content of the renderable.
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Constructor for Renderable.
     *
     * @param string $path The path to the renderable file.
     * @param array $data Optional data to be passed to the renderable.
     */
    protected function __construct(string $pathOrContent, array $data = [], bool $isContent = false)
    {
        $this->data = $data;
        if ($isContent) 
        {
            $this->content = $pathOrContent;
            $this->path = "";
        } 
        else 
        {
            $this->path = $pathOrContent;
            $this->content = file_get_contents($pathOrContent);
        }
    }

    /**
     * Returns the full path for a given renderable.
     *
     * @param string $path
     * @return string
     */
    protected static function getFullPath(string $path): string
    {
        $path = __DIR__ . "/../../pages" . static::getBasePath() . str_replace(".", "/", $path);
        return is_dir($path) ? $path . "/default.php" : $path . ".php";
    }

    /**
     * Returns the path to the CSS file for the given name.
     *
     * @param string $name The name of the CSS file.
     * @return string The full path to the CSS file.
     */
    private static function getJavascriptPath(string $name): string
    {
        return __DIR__ . "/../../resources/js/" . str_replace(".", "/", $name) . ".js";
    }

    /**
     * Returns the path to the CSS file for the given path.
     *
     * @param string $path The path of the CSS file.
     * @return string The full path to the CSS file.
     */
    private static function getCssPath(string $path): string
    {
        return __DIR__ . "/../../resources/css/" . str_replace(".", "/", $path) . ".css";
    }

    /**
     * Sets the renderable with the given name and data.
     * If the file does not exist, an exception is thrown.
     * @param string $name The name of the renderable.
     * @param array|null $data Optional data to be passed to the renderable.
     * @return static
     */
    public static function set(string $name, ?array $data = []): static
    {
        $path = self::getFullPath(path: $name);
        if (!file_exists($path))
            new InternalException(
                "Renderable file not found: {$path}",
            );

        return new static($path, $data);
    }

    /**
     * Merges additional data into the renderable.
     *
     * @param array $data The data to merge.
     * @return static
     */
    public function with(array $data): static
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * Renders the view.
     *
     * @return string
     */
    private function render(): string
    {
        // Get the template engine instance
        $templateEngine = TemplateEngine::getInstance($this);

        /** Render the children recursively, 
         * then if the renderable is a component, return its content
         * otherwise, render the variables and extract the data, evaluate the content and return it
         */
        if ($this instanceof INestable)
            $templateEngine->renderChildren();

        $templateEngine->renderInstructions();

        if ($this instanceof Component)
            return $this->content;

        if ($this instanceof View)
            $templateEngine->renderLayout();

        $templateEngine->renderVariables();
        $templateEngine->renderJavascript();
        $templateEngine->renderCss();

        ob_start();
        extract($this->data, EXTR_SKIP);
        eval('?>' . $this->content);
        return ob_get_clean();
    }

    /**
     * Checks if a view exists.
     *
     * @param string $view
     * @return bool
     */
    public static function exists(string $view): bool
    {
        return file_exists(self::getFullPath(path: $view));
    }

    /**
     * Adds a CSS file to the view.
     *
     * @param string $name The name of the CSS file.
     * @return self
     */
    public function css(string $name): self
    {
        $path = "/src/resources/css/" . str_replace(".", "/", $name) . ".css";
        $fullPath = self::getCssPath(path: $name);

        // Check if the CSS file exists, if not throw an exception
        if (file_exists($fullPath))
            $this->css[$name] = $path;
        else 
            new InternalException(
                message:"CSS file not found: " . $name,
            );

        return $this;
    }

    /**
     * Checks if the view has any CSS files.
     *
     * @return bool True if the view has CSS files, false otherwise.
     */
    public function hasCss(): bool
    {
        return !empty($this->css);
    }

    /**
     * Adds a JS file to the view.
     *
     * @param string $name The name of the JS file.
     * @return self
     */
    public function js(string $name): self
    {
        $path = "/src/resources/js/" . str_replace(".", "/", $name) . ".js";
        $fullPath = self::getJavascriptPath(name: $name);

        if (file_exists($fullPath))
            $this->js[$name] = $path;
        else
            new InternalException(
                message:"JS file not found: " . $name,
            );

        return $this;
    }

    /**
     * Checks if the view has any JS files.
     *
     * @return bool True if the view has JS files, false otherwise.
     */
    public function hasJs(): bool
    {
        return !empty($this->js);
    }
}