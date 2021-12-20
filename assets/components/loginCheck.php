<?php
session_start();

if (isset($_SESSION["userId"]) || isset($_COOKIE['rememberLoggedIn'])) {
    return;
} else {
    header("Location: ./login.php");
}