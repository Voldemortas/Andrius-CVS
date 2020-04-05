<?php
/**
 * Created by PhpStorm.
 * User: simanaitis
 * Date: 18.12.28
 * Time: 13.09
 */
//DO NOT OVERRIDE THIS PAGE
if(substr($_SERVER['REQUEST_URI'], -1) != '/') {
    header('Location: ' . $_SERVER['REQUEST_URI'] . '/');
}else{
    include_once 'template.php';
}