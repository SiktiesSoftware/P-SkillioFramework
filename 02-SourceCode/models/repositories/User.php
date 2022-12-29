<?php
include_once __DIR__."/../../core/database/RepositoryModel.php";

/**
 * Manage the user repository
 * 
 * @package RepositoryModel => Base model repository
 */
class User extends RepositoryModel
{
    public int $id;                                   // Id of the user
    public string $nickname, $password, $role;        // Nickname, Password (HASH), role of the user

    /**
     * User class constructor
     * 
     * @param id => Id of the user
     * @param nickname => Nickname of the user
     * @param password => Password of the user
     * @param role => Role of the user
     */
    public function __construct(int $id, string $nickname, $password, $role)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->password = $password;
        $this->role = $role;
    }
}
?>