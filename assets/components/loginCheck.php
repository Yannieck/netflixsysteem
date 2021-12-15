<?php
session_start();
if ($_SESSION['loggedIn'] == 1 || $_COOKIE['rememberLoggedIn'] == 1) {
    return;
} else {    
    header("Location: ./login.php");
}
