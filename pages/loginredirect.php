<?php

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

session_start();
$_SESSION["userId"] = $id;

if (isset($_GET['rem'])) {
    if ($_GET['rem'] == 1) {
        setcookie("rememberLoggedIn", true, time() + (60 * 60 * 24));
        header("Location: ./main.php");
    } else {
        echo "Don't enable cookie";
        header("Location: ./main.php");
    }
} else {
    echo "ERROR 404: page not found.";
}