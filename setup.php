<?php

//////////////////////////////////////////////
//                                          //
// File:        setup.php                   //
// Created:     08-12-2021                  //
// By:          Joris Hummel                //
// Description: Setup database script.      //
//                                          //
//////////////////////////////////////////////

$host = "localhost";
$user = "root";
$pwd = "";

function DBExec($connection, $sqlStatement) {
    $stmt = mysqli_prepare($connection, $sqlStatement) 
        OR DIE("Preperation Error Occured: " . mysqli_error($connection) . ". <br> " . $sqlStatement);

    mysqli_stmt_execute($stmt)
        OR DIE("Something went wrong.");

    mysqli_stmt_close($stmt);
}


$conn = mysqli_connect($host, $user, $pwd) 
    OR DIE('Cannot connect to the database.');

$filename = "./null_pointer_videos.sql";
$handle = fopen($filename, "r");

$content = fread($handle, filesize($filename));

$sql = explode(";", $content);


foreach ($sql as $singleSQL) {
    if (strpos($singleSQL, "USE ")) {
        mysqli_select_db($conn, 'null pointer videos');
    } else {
        DBExec($conn, $singleSQL);
    }
}


mysqli_close($conn);


fclose($handle);

echo "Successfully done!"
?>