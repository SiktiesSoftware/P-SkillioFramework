<?php
namespace skillio\core\interfaces;

use skillio\core\utils\content\Component;

/**
 * Interface INestable
 * Represents a component that can have nested children.
 *
 * @package skillio\core\utils
 */
interface INestable
{
    /**
     * Get the children components.
     *
     * @return array The array of child components.
     */
    public function getChildren(): array;

    /**
     * Add a child component to the current component.
     *
     * @param string $name The name of the child component.
     * @param string|Component $content The content or component to be added as a child.
     * @return self Returns the current instance for method chaining.
     */
    public function children(string $name, string|Component $content): self;
}
?>