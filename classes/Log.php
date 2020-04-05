<?php

class Log extends DatabaseLike
{
    public $id;
    public $ip;
    public $url;
    public $error;
    public $user;
    public $time;

    /**
     * User constructor.
     * @param string $ip
     * @param string $url
     * @param int $error
     * @param int $user
     * @param string $time
     * @param int $id
     */
    public function __construct($ip = '', $url = '', $error = 0, $user = 0, $time = '', $id = 0)
    {
        $this->url = $url;
        $this->ip = $ip;
        $this->error = $error;
        $this->user = $user;
        $this->time = $time;
        $this->id = $id;
    }

    /**
     * @return BasicInfo
     */
    public static function properties()
    {
        $result = new BasicInfo('log', Log::class);
        $result->add(new Property('id','int'));
        $result->add(new Property('ip','string'));
        $result->add(new Property('url','string'));
        $result->add(new Property('error','int'));
        $result->add(new Property('time','string'));
        $result->add(new Property('user','int'));
        return $result;
    }}