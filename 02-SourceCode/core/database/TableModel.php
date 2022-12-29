<?php
/**
 * Manage the tables for the db
 */
abstract class TableModel
{
    /**
     * Get all the selectionned table 
     * 
     * @return array => array of all the table
     */
    public static abstract function GetAll() : array;
}

?>