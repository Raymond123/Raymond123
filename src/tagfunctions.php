<?php

require "mysqlCon.php";
$qry = "select objThumbnail from galleryobjs";

class tagfunctions
{

    function dbcon($db){

        return mySqlCon($db);

    }

    function getTag($tag){

        $conn = $this->dbcon("gallery");
        $id = $conn->prepare("select objTags from galleryobjs where objName='" . $tag . "';");
        $id->execute();
        return $id->fetchColumn();

    }

    function tagfilter($int, $tag)
    {

        if ($tag != null) {

            if($int == 1) { return "select objThumbnail from galleryobjs where objTags='" . $tag . "';"; }
            //echo $qry;

        }

        return $this->getQry();

    }

    function getQry(){

        global $qry;
        return $qry;
        
    }

    function create_tag_list(){
        //session_start();

        $conn = $this->dbcon("gallery");
        $tag = $conn->prepare("select DISTINCT objTags from gallery.galleryobjs;");
        $tag->execute();

        echo "<div class='dropdown-menu'>";
        echo "<a class='dropdown-item' href='gallery.php'>No Filter</a>";

        for($i=0; $i<($tag->rowCount()); $i++){

            $tagName = $tag->fetchColumn();
            echo "<a class='dropdown-item' href='gallery.php?filter=". $tagName ."'>" . $tagName . "</a>";

        }

        echo "</div>";

    }

    function loadImage($input){

        $input_split = explode('/', $input);

        $myDir = 'M:/tempTest/'. $input_split[2] .'/'.$input_split[3];
        $mainDir = scandir($myDir);
        sort($mainDir, SORT_NUMERIC);

        $img_prev=null;
        $img_next=null;
        $index=0;

        foreach ($mainDir as $imgPath){

            if($input_split[4] == $imgPath){

                if(!empty($mainDir[$index-1])){$img_prev = $mainDir[$index - 1];}
                if(!empty($mainDir[$index+1])){$img_next = $mainDir[$index + 1];}
                break;

            }

            $index++;
        }

        $img = file_get_contents($input);
        $img_codes = base64_encode($img);
        echo isset($img_prev) . isset($img_next);

        echo "<div class='container'>";
        echo "<div class='row justify-content-center'>";
        echo "<div class='col-6'>";
        echo '<img src="data:image/jpg;charset=utf-8;base64,'. $img_codes .'" class="img-fluid" alt="Responsive image"/>';
        echo "</div>";
        //echo "</div>";
        //echo "<div class='row justify-content-md-center'>";
        echo "<div class='col-md-auto'>";
        if(isset($img_prev)){echo '<a href="singleimage.php?page='. $myDir . "/" . $img_prev .'">';}
        echo '<button type="button" class="btn btn-info btn-lg">Previous</button>';
        echo '</a>';
        //echo "</div>";
        echo '<br><br>';
        //echo "<div class='col-md-auto'>";
        if(isset($img_next)){echo '<a href="singleimage.php?page='. $myDir . "/" . $img_next .'">';}
        echo '<button type="button" class="btn btn-info btn-lg">Next</button>';
        echo '</a>';
        echo '<br><br>';
        //echo "<div class='col-md-auto'>";
        echo '<a href="imgset.php?setname='. $input_split[3] .'">';
        echo '<button type="button" class="btn btn-info btn-lg">Back to Gallery</button>';
        echo '</a>';
        echo "</div>";
        echo "</div>";
        echo "</div>";
        //echo "</div>";
        return true;

    }

    function loadImages($int, $input)
    {
        $tag_in_gallery = true;
        $rowCount = 0;
        if ($int == 1) {
            $qry = $this->tagfilter($int, $input);

            $conn = $this->dbcon("gallery");
            $thumbnail = $conn->prepare($qry);
            $thumbnail->execute();
            $rowCount = ($thumbnail->rowCount())/3;

            if($rowCount<1){

                $tag_in_gallery = false;
                $table_tag = str_replace(" ", "_", $input) . "_";
                $tag_con = $this->dbcon("tags");
                $tag_names = $tag_con->prepare("select `setName` from `" . $table_tag . "`;select ");
                $tag_names->execute();
                $rowCount = ($tag_names->rowCount())/3;

            }

        }else if ($int == 0) {
            $tag = $this->getTag($input);
            $myDir = 'M:/tempTest/'. $tag .'/'.$input;
            $mainDir = scandir($myDir);
            sort($mainDir, SORT_NUMERIC);

            $rowCount = count($mainDir)/3;
        }

        $index = 0;
        $find_page = 0;

        for($i=0; $i<$rowCount; $i++){

            echo "<div class='row justify-content-md-center'>";

            for ($k=0; $k<3; $k){

                if($int==1) {
                    if (!$tag_in_gallery) {
                        $thumbnail = $conn->prepare("select objThumbnail from galleryobjs where objName='" . $tag_names->fetchColumn(). "';");
                        $thumbnail->execute();
                    }

                    $imgpath = $thumbnail->fetchColumn();
                }else if($int==0){
                    if(empty($mainDir[$index])){
                        break;
                    }
                    $imgpath = $myDir . '/' . $mainDir[$index];
                    $index++;
                }

                if($imgpath==null){
                    break;
                }

                try {
                    if(!strpos($imgpath, 'jpg')) {
                        $find_page++;
                        throw new Exception;
                    }
                    echo "<div class='col-3'>";
                    echo "<br>";
                    echo '<figure class="figure bg-info rounded" style="padding: 3px">';
                    $img = file_get_contents($imgpath);
                    $img_codes = base64_encode($img);

                    if ($int == 1) {
                        $name = $conn->prepare("select objName from galleryobjs where objThumbnail='" . $imgpath . "';");
                        $name->execute();
                        $tagName2 = $name->fetchColumn();

                        //echo "<h5>" . $tagName . "</h5>";
                        $tagName = urlencode($tagName2);
                        echo "<a href='imgset.php?setname=" . $tagName . "' target='_blank'>";
                    } else if ($int == 0) {
                        //echo $imgpath;
                        echo "<a href='singleimage.php?page=" . urlencode($imgpath) . "'>";
                    }
                    echo '<img src="data:image/jpg;charset=utf-8;base64,' . $img_codes . '" class="img-fluid img-thumbnail" alt="Responsive image"/>';
                    echo "</a>";
                    $page = $index-$find_page;
                    if($int==1){ echo '<figcaption class="figure-caption text-light">'.$tagName2.'</figcaption>'; }
                    else{ echo '<figcaption class="figure-caption text-light">page: ' . $page . '</figcaption>'; }
                    echo '</figure>';
                    echo "</div>";
                    $k++;
                } catch (Exception $e){



                }

            }

            echo "</div>";

        }
        return true;

    }

    function listTables(){

        $conn = $this->dbcon("tags");
        $table_list = $conn->prepare("SHOW TABLES");
        $table_list->execute();

        foreach (($table_list->fetchAll()) as $table){
            $output = str_replace('_', " ", $table[0]);

            echo '<div class="col-lg-auto">';
            echo '<figure class="figure bg-secondary rounded" style="padding: 0px 7px 0px 7px;">';
            echo '<a href="gallery.php?filter='. $output .'">';
            echo '<p class="h5 text-light">' . $output . '</p>';
            echo '</a>';
            echo '</figure>';
            echo '</div>';

        }

    }

}