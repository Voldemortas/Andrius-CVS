<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.26
 * Time: 10.50
 * Update 11:09
 */

class Submenu extends DatabaseLike
{
    public $id;
    public $text;
    public $url;
    public $roles;
    public $menu;

    /**
     * User constructor.
     * @param string $text
     * @param string $url
     * @param int $roles
     * @param int $menu
     * @param int $id
     */
    public function __construct($text = '', $url = '', $roles = 0, $menu = null, $id = 0)
    {
        $this->text = $text;
        $this->url = $url;
        $this->roles = $roles;
        $this->menu = $menu;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('submenu', Submenu::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('text','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('roles','int'));
        $result->add(new Property('menu','int'));
        return $result;
    }
}