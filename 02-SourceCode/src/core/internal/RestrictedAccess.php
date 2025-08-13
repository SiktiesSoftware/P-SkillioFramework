<?php
namespace skillio\core\internal;

/**
 * Internal framework interface for restricted access
 * Users should never implement this interface directly
 * This interface is used to control access to sensitive methods
 */
interface RestrictedAccess
{
    /**
     * Creates an instance of the class implementing this interface.
     *
     * @param RestrictedAccessFactory $factory The factory to create the instance.
     * @return mixed The created instance.
     */
    public static function create(RestrictedAccessFactory $factory): mixed;
}
?>