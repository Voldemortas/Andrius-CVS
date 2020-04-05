<?php

class Page extends DatabaseLike
{
    public $id;
    public $title;
    public $url;
    public $roles;

    /**
     * User constructor.
     * @param string $url
     * @param string $title
     * @param int $roles
     * @param int $id
     */
    public function __construct($url = '', $title = '', $roles = 0, $id = 0)
    {
        $this->url = $url;
        $this->title = $title;
        $this->roles = $roles;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('page', Page::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('title','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('roles','int'));
        return $result;
    }}