<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

include_once __DIR__."/../../core/form/Complements/Date.php";

/**
 * Manage the verifications
 */
class Verifications
{
    private DataRequest $request;             // Datas from the request

    /**
     * Class constructor
     * 
     * @param request => Data request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }
    
    /**
     * Verify if the field is set 
     * 
     * FIELD REQUIRED
     */
    public function Required()
    {

    }

    /**
     * Verify if the field have a maximum of X characters
     * 
     * MAXIMUM X CHARACTERS
     */
    public function Max(int $max)
    {

    }

    /**
     * Check if the field is a date and what date it have to be 
     * 
     * DATE FIELD MAY BE SET AT A CORRECT DATE
     */
    public function Date() : Date
    {
        return new Date();
    }
}
?>