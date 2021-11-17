<?php
include "tags.php";
global $var;
//session_start();
$_SESSION['pagelink'] = null;

if(isset($_GET['page'])){

    $_SESSION['pagelink'] = $_GET['page'];

}

//echo $_SESSION['tag'];
$send = str_replace("&amp", "&", $_SESSION['name']);
echo getTagFunc()->loadImage($_SESSION['pagelink']);
