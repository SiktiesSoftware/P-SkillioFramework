<?php
include_once __DIR__."/../../core/database/TableModel.php";
include_once __DIR__."/../../core/database/Database.php";

class t_user extends TableModel
{
    /**
     * Get a specific user
     * 
     * @param id => Id of the user
     * 
     * @return array => User object
     */
    public static function GetUserByIdUser(int $idUser) : array
    {
        // Get specific user
        $userArray = Database::GetInstance()->QueryPrepareExecute
        (
            "SELECT * FROM t_user WHERE idUser = :id",
            [["param" => "id", "value" => $idUser, "type" => PDO::PARAM_INT]]
        );

        // Return a single user
        return $userArray[0];
    }

    /**
     * Get all the user of the db
     * 
     * @return array => User object
     */
    public static function GetAll() : array
    {
        // Get all the users
        $session = Database::GetInstance()->QuerySimpleExecute
        (
            "SELECT * FROM t_session"
        );
        
        // Return users
        return $session;
    }
}

?>