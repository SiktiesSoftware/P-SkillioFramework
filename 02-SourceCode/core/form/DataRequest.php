<?php
/**
 * Manage the Data requests
 */
class DataRequest
{
    // Datas requested from $_POST global variable           
    // Errors for the requested datas
    public array $datas, $errors;           

    /**
     * Class constructor
     */
    function __construct()
    {
        
    }

    /**
     * Validate the datas from the datas of the sended form
     * 
     * @param dataInformations => Data informations for the validations
     * @return array => Array of informations sended
     */
    public function Validate(array $dataInformations)
    {
        // Check all the informations
        foreach ($this->datas as $key => $data) 
        {

        }
    }

    /**
     * Validate the datas from the datas of the sended form and stock errors
     * 
     * @param dataInformations => Data informations for the validations
     * @return array => Array of informations sended
     */
    public function ValidateWithErrors(array $dataInformations)
    {

    }
    
    /**
     * Get the old datas entered on the form
     * 
     * @return array => array of old datas
     */
    public function Old()
    {

    }
}
?>