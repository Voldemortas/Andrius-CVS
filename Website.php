<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.27
 * Time: 00.52
 */

class Website
{
    public $name;
    public $url;
    public $logo;
    /**
     * User constructor.
     * @param string $name
     * @param string $url
     * @param string $logo
     */
    public function __construct($name, $url, $logo)
    {
        $this->name = $name;
        $this->url = $url;
        $this->logo = $logo;
    }
    //sql functions
    /**
     * @return string
     */
    function insert(){
        return "INSERT INTO website (name, url, logo) VALUES (
            '". SQLite3::escapeString ($this->name)."', 
            '". SQLite3::escapeString ($this->url)."', 
            '". SQLite3::escapeString ($this->logo)."'
            );";
    }
}