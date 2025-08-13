<?php
namespace skillio\core\routing\response;

use skillio\core\routing\Response;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        $headers = ["Location" => $url];
        parent::__construct(
            content: "",
            statusCode: 302, // HTTP status code for redirection
            headers: $headers
        );
    }
}
