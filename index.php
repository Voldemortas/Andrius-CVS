<?php

if(file_exists('install.php')){
    header('location: install.php');
    die();
}

header('location: news/');
