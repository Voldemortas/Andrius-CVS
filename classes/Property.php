<?php
class Property
{
    public $name;
    public $type;

    /**
     * Tuple constructor.
     * @param string $name
     * @param string $type
     */
    public function __construct($name, $type)
    {
        $this->name = $name;
        $this->type = $type;
    }
}