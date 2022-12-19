<?php
class User
{
    public int $id;                 // Id of the user
    public string $nickname;        // Nickname of the user
    public string $password;        // Password of the user (HASH)
    
    /**
     * User class constructor
     * 
     * @param id => Id of the user
     * @param nickname => Nickname of the user
     * @param password => Password of the user
     */
    public function __construct(int $id, string $nickname, $password)
    {
        $this->id = $id;
        $this->nickname = $nickname;
        $this->password = $password;
    }
}
?>