<?php
namespace skillio\core\utils\content;

use skillio\core\interfaces\INestable;
use skillio\core\internal\exceptions\InternalException;
use skillio\core\utils\Renderable;

/**
 * Class View
 * Represents a view that can be rendered with a layout and nested components.
 *
 * @package skillio\core\utils\content
 */
class View extends Renderable implements INestable
{
    /** @var string|null */
    protected ?string $title;

    /** @var Layout|null */
    protected ?Layout $layout;

    /** @var array<string,Component|string>|null */
    protected ?array $children = [];

    /**
     * Returns the base path for views.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return "/views/";
    }

    /**
     * Gets the children components of the view.
     * 
     * @return array The array of child components.
     */
    public function getChildren(): array
    {
        return $this->children ?? [];
    }

    /**
     * Sets the title for the view.
     *
     * @param string $title
     * @return self
     */
    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Checks if the view has a title set.
     *
     * @return bool True if the view has a title, false otherwise.
     */
    public function hasTitle(): bool
    {
        return isset($this->title);
    }

    /**
     * Gets the title of the view.
     *
     * @return string|null The title of the view or null if not set.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Sets the layout for the view.
     *
     * @param string $name
     * @return self
     */
    public function layout(string $name): self
    {
        $this->layout = Layout::set(name: $name);
        return $this;
    }

    /**
     * Checks if the view has a layout set.
     *
     * @return Layout|null The layout of the view or null if not set.
     */
    public function getLayout(): ?Layout
    {
        return $this->layout;
    }   

    /**
     * Checks if the view has a layout set.
     *
     * @return bool True if the view has a layout, false otherwise.
     */
    public function hasLayout(): bool
    {
        return isset($this->layout);
    }

    /**
     * Sets the children for the view.
     *
     * @param string $name
     * @param string|Component $content
     * @return self
     */
    public function children(string $name, string|Component $content) : self
    {
        $this->children[$name] = $content;
        return $this;
    }
}
?>
