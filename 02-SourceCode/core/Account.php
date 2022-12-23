<?php
/**
 * Manage the accounts
 */
class Account
{
    /**
     * Define if the user has some role or not
     */
    public function HasRole(array $roles)
    {
        // Check if the user isset
        if (isset($this)) 
        {
            // Get all the allowed roles of the page
            foreach ($roles as $key => $role) 
            {
                // Check if the user has some role
                if ($this->role == $role) 
                {
                    return true;
                }
            }
        }
    }
}
?>