<?php
namespace skillio\core\internal;

/**
 * Factory class for creating instances of classes that implement RestrictedAccess.
 * This class is used to control access to sensitive methods and ensure that only
 * authorized classes can create instances of restricted classes.
 *
 * @package skillio\core\internal
 */
class RestrictedAccessFactory
{
    /** @var string The caller class name */
    private string $caller;

    /** @var array<string> The allowed namespaces for internal access */
    private array $allowedNamespaces = 
    [
        // 'skillio\\core\\',
        'src\\core\\',
        'src\\routes\\',
    ];

    /**
     * Gets the caller class name.
     *
     * @return string The caller class name.
     */
    public function getCaller(): string
    {
        return $this->caller;
    }

    /**
     * Constructor for RestrictedAccessFactory.
     * Initializes the caller class name.
     */
    public function __construct()
    {
        $this->caller = debug_backtrace()[1]['file'] ?? '';
    }

    /**
     * Creates an instance of the class implementing RestrictedAccess.
     *
     * @param string $className The class name to create an instance of.
     * @return mixed The created instance.
     * @throws InternalException If access is denied.
     */
    public function validateInternalAccess(): bool
    {
        $valid = false;

        // Only allow framework namespaces
        foreach ($this->allowedNamespaces as $namespace) 
            if (str_starts_with($this->caller, dirname(__DIR__, 3) . "\\" . $namespace)) 
                $valid = true;
            
        return $valid;
    }
}