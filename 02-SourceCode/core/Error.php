<?php
/**
 * Manage the errors
 */
class Error
{
    public string $title, $description;
    public ?int $errorCode;

    /**
     * Class constructor
     * 
     * @param title => Title of the error
     * @param description => Description of the error
     * @param ?errorCode => Potential code of the error
     */
    function __construct(string $title, $description, ?int $errorCode)
    {
        $this->title = $title;
        $this->description = $description;
        isset($errorCode) ? $this->errorCode = $errorCode : $this->errorCode = null;
    }
}
?>