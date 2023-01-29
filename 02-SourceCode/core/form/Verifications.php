<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

include_once __DIR__."/../../core/form/Complements/Date.php";

/**
 * Manage the verifications
 */
class Verifications
{
    /**
     * Class constructor
     * 
     * @param request => Data request
     */
    public function __construct()
    {

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
     * 
     * @param max => Maximum of characters possible
     */
    public function Max(int $max)
    {

    }

    /**
     * Verify if the field have a maximum of X characters
     * 
     * MAXIMUM X CHARACTERS
     * 
     * @param min => Minimum of characters possible
     */
    public function Min(int $min)
    {

    }

    /**
     * Check if the field is a date and what date it have to be 
     * 
     * DATE FIELD MAY BE SET AT A CORRECT DATE
     * 
     * @return ?Date => Maybe date object
     */
    public function Date() : ?Date
    {
        return new Date();
    }
}
?>