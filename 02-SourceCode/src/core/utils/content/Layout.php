<?php
namespace skillio\core\utils\content;

use skillio\core\utils\Renderable;
use skillio\core\utils\INestable;

/**
 * Class Layout
 * Represents a layout that can be used to structure the content of a page.
 *
 * @package skillio\core\utils\content
 */
class Layout extends Renderable
{
    /**
     * Returns the base path for layouts.
     *
     * @return string
     */
    protected static function getBasePath(): string
    {
        return "/layouts/";
    }
}
?>