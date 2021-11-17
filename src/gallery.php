<?php
include "tags.php";
global $var;
//session_start();
$_SESSION['tag'] = null;

if(isset($_GET['filter'])){

    $_SESSION['tag'] = $_GET['filter'];

}

//echo $_SESSION['tag'];
if(($page = getTagFunc()->loadImages(1, $_SESSION['tag']))){

    echo $page;

}