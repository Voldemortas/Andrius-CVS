<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.28
 * Time: 15.47
 */

unset($_SESSION["user"]);
header('Location: '.$website->url.'news/');