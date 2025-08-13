<?php
namespace skillio\core\utils\content;

use skillio\core\interfaces\INestable;
use skillio\core\utils\Renderable;

/**
 * Class Component
 * Represents a component that can have nested children.
 *
 * @package skillio\core\utils\content
 */
class Component extends Renderable implements INestable
{
    /** @var array<string,Component|string>|null */
    private ?array $children = [];

    /**
     * Returns the base path for components.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return "/components/";
    }

    /**
     * Sets the children for the view.
     *
     * @param string $name
     * @param string|Component $content
     * @return self
     */
    public function getChildren(): array
    {
        return $this->children ?? [];
    }

    /**
     * Sets the children for the component.
     *
     * @param string $name
     * @param string|Component $content
     * @return self
     */
    public function children(string $name, string|Component $content): self
    {
        $this->children[$name] = $content;
        return $this;
    }
}
?>