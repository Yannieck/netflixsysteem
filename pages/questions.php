<?php
require '../utils/dbconnect.php';
require '../utils/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../assets/components/head.php'; ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/questions/style.css">
</head>
<body>
   <?php 
   require_once '../assets/components/header.php'; 
   ?> 
   <div class="container">
       <?php

        $sql = "SELECT Id,Title,AskDate FROM question ORDER BY AskDate DESC";
        $questions = stmtExecute($conn, $sql, 2);

        echo "<div class='questions__wrapper'>";

        foreach ($questions["Title"] as $index => $title) {

            $askDate = $questions["AskDate"][$index];
            $id = $questions["Id"][$index];

            echo "
            <div class='question'>
                <div class='question__title'>
                    <a href='?title=$id'>
                        <h2>$title</h2>
                    </a>
                </div>
                <div class='question__info'>
                    <div class='question__tags'>"; 

            $sql = "SELECT SubCategory FROM subtag WHERE Id IN (SELECT SubTagId FROM tag_question WHERE QuestionId = ?)";

            $tags = stmtExecute($conn, $sql, 1, "i", $id);
            foreach($tags["SubCategory"] as $index => $TagName) {
                echo "<p class='tag'>$TagName</p>";
            }
            echo "</div>
                    <div class='question__ask-date'>
                        <p>asked ".calculateDate($askDate)." ago</p>
                    </div>
                </div>
            </div>
            ";
        }
        echo "</div>";

       ?>
   </div>
</body>
</html>
