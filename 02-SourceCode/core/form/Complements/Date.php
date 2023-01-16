<?php
/**
 * Manage the dates verifications
 */
class Date
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Set the date before than one selected
     * 
     * @param date => Date before the user can enter
     */
    public function Before(DateTime $date)
    {

    }

    /**
     * Set the date after than one selected
     * 
     * @param date => Date before the user can enter
     */
    public function After(DateTime $date)
    {

    }

    /**
     * Set the date at a precise time selected
     * 
     * @param date => Date before the user can enter
     */
    public function At(DateTime $date)
    {

    }

    /**
     * Set the date for today 
     */
    public function Today()
    {
        $date = new DateTime();
    }
}
?>