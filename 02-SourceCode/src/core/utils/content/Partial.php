<?php
namespace skillio\core\utils\content;

use skillio\core\interfaces\INestable;
use skillio\core\utils\Renderable;

/**
 * Class Partial
 * Represents a partial that can be used to include reusable content in views.
 *
 * @package skillio\core\utils\content
 */
class Partial extends Renderable implements INestable
{
    /** @var array<string,Component|string>|null */
    private ?array $children = [];

    /**
     * Returns the base path for Partials.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return "/includes/";
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
     * Sets the children for the Partial.
     *
     * @param string $name
     * @param string|Partial $content
     * @return self
     */
    public function children(string $name, string|Component $content): self
    {
        $this->children[$name] = $content;
        return $this;
    }
}
?>