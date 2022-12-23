<?php
class Group
{
    public string $name;

    public function __construct()
    {
        
    }

    public function Name(string $name) : Group
    {
        $this->name = $name;
        return $this;
    }
}
?>