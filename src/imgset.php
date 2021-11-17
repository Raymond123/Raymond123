<?php
include "tags.php";
//global $var;
//session_start();
$_SESSION['name'] = null;

if(isset($_GET['setname'])){

    $_SESSION['name'] = urldecode($_GET['setname']);

}

$send = str_replace("&amp", "&", $_SESSION['name']);
echo getTagFunc()->loadImages(0, $send);
