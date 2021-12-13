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
$column_names = ["name", "type", "size", "location"];

$conn = mysqli_connect($host, $user, $pwd, $db)
    or die('Cannot connect to the database.');

// Functie voor INSERT INTO, DELETE en dat soort querry's.
$executeQuerry = function (string $querry) use ($conn) {
    $stmt = mysqli_prepare($conn, $querry) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    mysqli_stmt_close($stmt);
};

// Functie die een output geeft, voor SELECT querry's.
$returnQuerry = function (string $querry) use ($conn, $column_names) {
    $stmt = mysqli_prepare($conn, $querry) or die(mysqli_error($conn));
    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
    mysqli_stmt_close($stmt);

    $result = mysqli_query($conn, $querry);
    $resultCheck = mysqli_num_rows($result);

    // Kijk of er resultaten zijn
    if ($resultCheck > 0) {
        // Zet de waarden in een 2D array zodat het makkelijk te returnen en te lezen is.
        $array = array();
        $index = 0;
        // Haal alle resultaten op
        while ($row = mysqli_fetch_assoc($result)) {
            // Maak een nieuwe array aan.
            $array[$index] = array();
            // Loop door alle kolom namen en stop ze in een row en voeg ze toe aan de array
            for ($column = 0; $column < count($column_names); $column++) {
                // Voeg de resultaten toe aan de array
                array_push($array[$index], $row[$column_names[$column]]);
            }
            $index++;
        }
        return $array;
    }
};
