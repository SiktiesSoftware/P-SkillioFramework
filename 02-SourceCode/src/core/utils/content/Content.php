<?php
namespace skillio\core\utils\content;

use skillio\core\utils\Renderable;

/**
 * Class Layout
 * Represents a layout that can be used to structure the content of a page.
 *
 * @package skillio\core\utils\content
 */
class Content extends View
{
    /**
     * Set the content for the layout.
     * @param string $content The content to set.
     * @param array|null $data Optional data to pass to the content.
     * @return static
     */
    public static function set(string $content, ?array $data = []): static
    {
        return new static($content, $data, true);
    }
}
?>