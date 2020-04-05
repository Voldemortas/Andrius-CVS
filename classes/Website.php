<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.27
 * Time: 00.52
 */

class Website extends DatabaseLike
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
    public function __construct($name = '', $url = '', $logo = '')
    {
        $this->name = $name;
        $this->url = $url;
        $this->logo = $logo;
    }


    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('website', Website::class);
        $result->add(new Property('name','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('logo','string'));
        return $result;
    }
}