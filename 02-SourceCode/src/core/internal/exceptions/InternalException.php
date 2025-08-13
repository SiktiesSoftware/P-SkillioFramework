<?php
namespace skillio\core\internal\exceptions;

use skillio\core\internal\ExceptionHandler;

/**
 * Class InternalException
 *
 * This class represents an internal exception that occurs within the application.
 * It extends the ExceptionHandler to provide a consistent way to handle internal errors.
 */
class InternalException extends ExceptionHandler
{
    /**
     * Constructor for InternalException.
     *
     * @param string $message The error message.
     * @param int $code The error code (default is 500).
     * @param \Throwable|null $previous The previous exception (if any).
     */
    public function __construct(string $message = "An internal error occurred.", int $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct(message: $message, code: $code, previous: $previous);
        $this->handle();
    }

    /**
     * Determines if the exception should display detailed information.
     *
     * @return bool
     */
    public function handle(): void
    {
        if ($this->shouldDisplayDetails())
            $this->render();

        exit;
    }
}
?>