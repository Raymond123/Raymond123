<?php

    function mySqlCon($db){
        try{
            //echo "Connection Status: ";
            $servername = "localhost";
            $username = "root";
            $password = "admin";

            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
            //$_SESSION['conn'] = $conn;
            //echo"Connected";

        } catch ( PDOException $e ){

            echo "Connection failed : " . e.getMessage();

        }
    }
    
    function closeCon(){

        $conn = null;

    }