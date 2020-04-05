<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.26
 * Time: 10.50
 * Update 11:09
 */

class User
{
    public $id;
    public $pastas;
    public $password;
    public $roles;

    /**
     * User constructor.
     * @param string $pastas
     * @param string $password
     * @param int $roles
     */
    function __construct($pastas, $password, $roles)
    {
        $this->pastas = $pastas;
        $this->password = $password;
        $this->roles = $roles;
    }
    //sql functions

    /**
     * @return string
     */
    function insert(){
        return "INSERT INTO user (email, password, roles) VALUES (
            '".SQLite3::escapeString ($this->pastas)."', 
            '".SQLite3::escapeString ($this->password)."', 
            ".$this->roles * 1 .");";
    }
}