<?php
session_start();
$_SESSION["loggedIn"] = True;

if (isset($_GET['rem'])) {
    if ($_GET['rem'] == 1) {
        setcookie("rememberLoggedIn", 1, time() + (60 * 60 * 24));
        header("Location: ./main.php");
    } else {
        echo "Don't enable cookie";
        header("Location: ./main.php");
    }
} else {
    echo "ERROR 404: page not found.";
}