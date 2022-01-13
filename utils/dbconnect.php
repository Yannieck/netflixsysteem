<?php

//////////////////////////////////////////////
//                                          //
// File:        dbconnect.php               //
// Created:     08-12-2021                  //
// By:          Joris Hummel                //
// Description: Connect with the database.  //
//                                          //
//////////////////////////////////////////////

$host = "localhost";
$user = "root";
$pwd = "";
$db = "null pointer videos";

$conn = mysqli_connect($host, $user, $pwd, $db)
    or die('Cannot connect to the database.');
