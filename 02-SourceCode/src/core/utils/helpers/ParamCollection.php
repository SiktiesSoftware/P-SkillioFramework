<?php
namespace skillio\core\utils\helpers;
use Countable;
use Iterator;
use ArrayAccess;

/**
 * ParamCollection - A collection class for managing parameters with array-like access and iteration capabilities.
 * 
 * This class implements Iterator, Countable, and ArrayAccess interfaces to provide:
 * - Array-like access to parameters using square bracket notation
 * - Iteration capabilities for foreach loops
 * - Count functionality
 * - Functional programming methods (map, filter, reduce, etc.)
 * 
 * @implements Iterator<int, mixed>
 * @implements ArrayAccess<int|string, mixed>
 */
class ParamCollection implements Iterator, Countable, ArrayAccess
{
    /** @var array<int|string,mixed> The internal array storing parameters */
    private array $params = [];
    
    /** @var int Current position for iterator */
    private int $position = 0;

    /**
     * Constructor - Initialize the collection with an array of parameters.
     * 
     * @param array<int|string, mixed> $params Initial parameters to store in the collection
     */
    public function __construct(?array $params)
    {
        $this->params = $params ?? [];
    }

    #region ==================== ArrayAccess Interface Methods ====================

    /**
     * Check if a parameter exists at the given offset.
     * 
     * @param mixed $offset The offset to check
     * @return bool True if the offset exists, false otherwise
     */
    public function offsetExists($offset): bool
    {
        return isset($this->params[$offset]);
    }

    /**
     * Get the parameter value at the given offset.
     * 
     * @param mixed $offset The offset to retrieve
     * @return mixed The value at the offset, or null if not found
     */
    public function offsetGet($offset): mixed
    {
        return $this->params[$offset] ?? null;
    }

    /**
     * Set a parameter value at the given offset.
     * 
     * @param mixed $offset The offset to set (null for append)
     * @param mixed $value The value to set
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        $this->params[$offset] = $value;
    }

    /**
     * Remove a parameter at the given offset.
     * 
     * @param mixed $offset The offset to remove
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->params[$offset]);
    }
    #endregion
    
    #region ==================== Iterator Interface Methods ====================

    /**
     * Get the current element in the iteration.
     * 
     * @return mixed The current element value, or null if position is invalid
     */
    public function current(): mixed
    {
        return $this->params[$this->position] ?? null;
    }

    /**
     * Get the current key/index in the iteration.
     * 
     * @return mixed The current position/key
     */
    public function key(): mixed
    {
        return $this->position;
    }

    /**
     * Move to the next element in the iteration.
     * 
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Reset the iterator to the beginning.
     * 
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Check if the current position is valid.
     * 
     * @return bool True if the current position has a valid element
     */
    public function valid(): bool
    {
        return isset($this->params[$this->position]);
    }
    #endregion

    #region ==================== Countable Interface Methods ====================

    /**
     * Get the number of parameters in the collection.
     * 
     * @return int The count of parameters
     */
    public function count(): int
    {
        return count($this->params);
    }
    #endregion

    #region ==================== Functional Programming Methods ====================

    /**
     * Map each element using a callback function.
     * 
     * @param callable $callback Function to apply to each element (value, key) => newValue
     * @return array<int|string, mixed> Array of mapped values
     */
    public function map(): array
    {
        $args = func_get_args();
        $callback = array_shift($args);

        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("The first argument must be a callable function.");
        }

        return array_map($callback, $this->params, array_keys($this->params));
    }

    /**
     * Filter elements using a callback function.
     * 
     * @param callable $callback Function that returns true for elements to keep
     * @return array<int|string, mixed> Filtered array of elements
     */
    public function filter(callable $callback): array
    {
        return array_filter($this->params, $callback);
    }

    /**
     * Reduce the collection to a single value using a callback function.
     * 
     * @param callable $callback Function to apply for reduction (accumulator, current) => result
     * @param mixed $initial Initial value for the accumulator
     * @return mixed The final reduced value
     */
    public function reduce(callable $callback, mixed $initial): mixed
    {
        return array_reduce($this->params, $callback, $initial);
    }

    /**
     * Split the collection into chunks of specified size.
     * 
     * @param int $size The size of each chunk (must be greater than 0)
     * @return array<int, array<int|string, mixed>> Array of chunks
     */
    public function chunk(int $size): array
    {
        return array_chunk($this->params, $size);
    }

    /**
     * Find all elements that match the given callback condition.
     * 
     * @param callable(int|string, mixed): bool $callback Function that returns true for the desired elements
     * @return array<int|string,mixed> Array of matching elements
     */
    public function find(callable $callback): array
    {
        $results = [];
        foreach ($this->params as $key => $value) {
            if ($callback($key, $value)) {
                $results[] = $value;
            }
        }
        return $results;
    }

    /**
     * Merge another array of parameters into this collection.
     * 
     * @param array<int|string, mixed> $params Array of parameters to merge
     * @return self The current instance with merged parameters
     */
    public function merge(?array $params): self
    {
        if ($params)
            $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Add a new parameter to the collection.
     * 
     * @param string $key The key for the new parameter
     * @param mixed $value The value for the new parameter
     * @return self The current instance with the new parameter added
     */
    public function add(string $key, mixed $value): self
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Get a parameter value by key, or return a default value if not found.
     * 
     * @param string $key The key of the parameter to retrieve
     * @param mixed $default Default value to return if the key does not exist
     * @return mixed The parameter value or the default value
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Get all parameters as an associative array.
     * 
     * @return array<int|string, mixed> The entire collection of parameters
     */
    public function all(): array
    {
        return $this->params;
    }
    #endregion
}
?>