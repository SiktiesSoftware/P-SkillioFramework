<?php
namespace skillio\core\internal;

/**
 * ExceptionHandler class for handling exceptions in the application.
 *
 * This class extends the base Exception class and provides methods to handle
 * exceptions, render error details, and manage stack traces.
 */
abstract class ExceptionHandler extends \Exception
{
    /**
     * Handles the exception.
     */
    public abstract function handle(): void;

    /**
     * Renders the exception details.
     *
     * @return void
     */
    protected function render(): void
    {
        // Set the last traces
        $lastTraces = [];
        $lastTraces[] = [
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'class' => null,
            'type' => null,
            'function' => null,
            'code' => $this->getRelevantCode(file: $this->getFile(), line: $this->getLine()),
        ];

        // Iterate through the stack trace and get the last traces
        foreach ($this->getTrace() as $item)
        {
            // Get the last trace file and line
            $lastTraceFile = $item['file'] ?? null;
            $lastTraceLine = $item['line'] ?? null;

            // If the trace file and line are set, add them to the last traces
            if ($lastTraceFile && $lastTraceLine) 
                $lastTraces[] = [
                    'file' => $lastTraceFile,
                    'line' => $lastTraceLine,
                    'class' => $item['class'] ?? null,
                    'type' => $item['type'] ?? null,
                    'function' => $item['function'] ?? null,
                    'code' => $this->getRelevantCode(file: $lastTraceFile, line: $lastTraceLine),
                ];
        }

        // Change the query parameter to trace
        $traceIndex = isset($_GET['trace']) ? (int)$_GET['trace'] : 0;
        
        // Render error page with stack trace
        include_once __DIR__ . '/exceptions/debug-error.php';
        throw $this;
    }

    /**
     * Gets the relevant code lines around the error line.
     *
     * @param string $file The file where the error occurred.
     * @param int $line The line number where the error occurred.
     * @return array<string>
     */
    private function getRelevantCode(string $file, int $line): array
    {
        // Get the relevant code lines around the error line
        if (!file_exists($file)) return [];
        
        $lines = file($file);
        $start = max(0, $line - 10);
        $end = min(count($lines), $line + 10);

        $allLines = str_replace(" ", "&nbsp;", array_slice($lines, $start, $end - $start, true));
        
        // protect against xss attacks and keep the spaces
        $allLines = str_replace("<", "&lt;", $allLines);
        $allLines = str_replace(">", "&gt;", $allLines);

        return $allLines;
    }

    /**
     * Determines if the exception should display detailed information.
     *
     * @return bool
     */
    protected function shouldDisplayDetails(): bool
    {
        // Check if in debug mode (from config)
        return strtolower($_ENV['DEBUG'] ?? '') == 'true';
    }
}