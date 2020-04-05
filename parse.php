<?php
include 'BBCode.php';
die(BBCode::parse($_POST['text']));