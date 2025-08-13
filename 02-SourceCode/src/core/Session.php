<?php
namespace skillio\core;

use ArrayObject;

class Session
{
    /** @var array The session key which will be removed after */
    private static array $tempsKeys = [];

    /**
     * Starts a new session or resumes the existing one.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) 
            session_start();
    }

    /**
     * Destroys the current session.
     */
    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_NONE) 
            session_destroy();
    }

    /**
     * Sets a session variable.
     *
     * @param string $key The key for the session variable.
     * @param mixed $value The value to set.
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Gets a session variable.
     *
     * @param string $key The key for the session variable.
     * @return mixed|null The value of the session variable, or null if not set.
     */
    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Checks if a session variable is set.
     *
     * @param string $key The key for the session variable.
     * @return bool True if the session variable is set, false otherwise.
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Unsets a session variable.
     *
     * @param string $key The key for the session variable to unset.
     */
    public static function unset(string $key): void
    {
        unset($_SESSION[$key]); // Use unset() instead of setting to null
    }

    /**
     * Clears all session variables.
     */
    public static function clear(): void
    {
        unset($_SESSION);
    }

    /**
     * Extracts a session variable and unsets it.
     *
     * @param string $key The key for the session variable to extract.
     * @return mixed|null The value of the session variable, or null if not set.
     */
    public static function extractAt(string $key): mixed
    {
        // Check if the session variable exists
        if (self::has(key: $key)) 
        {
            $data = self::get(key: $key);
            self::unset(key: $key); // Unset the session variable after extracting it
            return $data;
        }

        return null;
    }
}
