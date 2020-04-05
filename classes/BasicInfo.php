<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.27
 * Time: 22.13
 */

class BasicInfo
{
    public $database;
    public $properties;
    public $object;

    /**
     * BasicInfo constructor.
     * @param string $database
     * @param DatabaseLike $object
     */
    public function __construct($database, $object)
    {
        $this->database = $database;
        $this->object = $object;
        $this->properties = [];
    }

    /**
     * @param Property $property
     */
    public function add($property){
        $this->properties[] = $property;
    }
}