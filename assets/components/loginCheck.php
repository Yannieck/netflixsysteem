<?php
session_start();
if ($_SESSION['loggedIn'] == 1 || isset($_COOKIE['rememberLoggedIn'])) {
    return;
} else {    
    header("Location: ./login.php");
}
