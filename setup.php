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

// Connect to the database
$conn = mysqli_connect($host, $user, $pwd) 
    OR DIE('Cannot connect to the database.');

function DBExec($connection, $sqlStatement) {

    // Prepare the statement
    $stmt = mysqli_prepare($connection, $sqlStatement) 
        OR DIE("Preperation Error Occured: " . mysqli_error($connection) . ". <br> " . $sqlStatement);

    // Execute the statement
    mysqli_stmt_execute($stmt)
        OR DIE("Something went wrong: ". mysqli_error($connection));

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Open and read and close the file
$filename = "./null_pointer_videos.sql";
$handle = fopen($filename, "r");

$content = fread($handle, filesize($filename));

fclose($handle);

// Make a string to an array by seperating them at the ";"
$sql = explode(";", $content);


foreach ($sql as $singleSQL) {

    // USE .... is not supported with the prepare statement, so execute a different function to select the database.
    if (strpos($singleSQL, "USE ")) {

        // Select the database
        mysqli_select_db($conn, 'null pointer videos');
    } else {

        // See line 20
        DBExec($conn, $singleSQL);
    }
}


// Close the database connection
mysqli_close($conn);

echo "Successfully done!"

?>