<?php
/**
 * Manage the Data requests
 */
class DataRequest
{
    // Datas requested from $_POST global variable           
    // Errors for the requested datas
    public array $datas, $errors;    
    private bool $isValid = true;                       // Define if the verification is valid

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
     * @return bool => Boolean of informations sended
     */
    public function Validate(array $dataInformations)
    {
        // Create the verifications object
        $verifications = new Verifications();

        // Check all the informations
        foreach ($this->datas as $name => $data) 
        {              
            echo "<pre>";
            var_dump($name);
            echo "</pre>";

            echo "<pre>";
            var_dump($data);
            echo "</pre>";
            foreach ($dataInformations as $key => $functions) 
            {
                // Check if the field name is the same as the sended for the verification
                if ($key != $name) continue;

                // Separate the datas from the functions
                $func = $this->Separate($functions, ":");

                echo "<pre>";
                var_dump($func);
                echo "</pre>";

                foreach ($func as $key => $function) 
                {
                    echo "<pre>";
                    var_dump($function[0]);
                    echo "</pre>";
                    $callable = $function[0];
                    isset($function[1]) ? $data = $function[1] : $data = null;
                    $verifications->$callable($data);
                }
            }
        }
    }

    /**
     * Separate the datas from the functions
     * 
     * @param values => Values for the separations
     * @return array => Array of separated values 
     */
    private function Separate($datas, $separator)
    {
        $func = array();  

        foreach ($datas as $data) 
        {
            // Separate the functions
            $validations = explode($separator, $data);

            // Set the validations to the funcs array
            $func[] = $validations;
        }

        // Return funcs array
        return $func;
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
     * Validate the datas from the datas of the sended form and stock errors
     * 
     * @return bool => Boolean which define if the comparison is valid
     */
    public function Compare($value1, $value2) : bool    
    {
        // Check if the values are the same
        if ($value1 === $value2) 
        {
            return true;
        }

        return false;
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