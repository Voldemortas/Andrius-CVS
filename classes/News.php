<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.26
 * Time: 10.50
 * Update 11:09
 */

class News extends DatabaseLike
{
    public $id;
    public $text;
    public $title;
    public $url;
    public $roles;
    public $nonprivate;

    /**
     * User constructor.
     * @param string $text
     * @param string $url
     * @param string $title
     * @param int $roles
     * @param int $nonprivate
     * @param int $id
     */
    public function __construct($text = '', $url = '', $title = '', $roles = 0, $nonprivate = 0, $id = 0)
    {
        $this->text = $text;
        $this->url = $url;
        $this->title = $title;
        $this->roles = $roles;
        $this->nonprivate = $nonprivate;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('news', News::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('text','string'));
        $result->add(new Property('title','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('roles','int'));
        $result->add(new Property('nonprivate','int'));
        return $result;
    }}