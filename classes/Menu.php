<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.26
 * Time: 10.50
 * Update 11:09
 */

class Menu extends DatabaseLike
{
    public $id;
    public $text;
    public $url;
    public $roles;

    /**
     * User constructor.
     * @param string $text
     * @param string $url
     * @param int $roles
     * @param int $id
     */
    public function __construct($text = '', $url = '', $roles = 0, $id = 0)
    {
        $this->text = $text;
        $this->url = $url;
        $this->roles = $roles;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('menu', Menu::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('text','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('roles','int'));
        return $result;
    }}