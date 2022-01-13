<?php
/*
 * Answer a question with a URL
 * Voorwaardens:
 *      - 'answerQuestion.php?TitleId=?' & ? = valid 
 *      - het is een link van een bestaande video
 *      - De video is niet van jezelf http://localhost/School/Projects/netflixsysteem/pages/videopage.php?VideoId=1&Notifications=hidden
*/  

session_start();

// Require nessecary files:
require_once "../utils/functions.php";

if(isset($_GET['TitleId'])) {
    $id = filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT);
    $sql = "SELECT Id FROM question";
    $question = stmtExecute($sql, 1, "i", $id);

    if(in_array($id, $question["Id"])) {
        if(isset($_POST['submit'])) {
            $url = $_POST['url'];

            $description = $_POST['description'];

            if(str_contains($url, $_SERVER["HTTP_HOST"]) && str_contains($url, "VideoId=")) {
                $videoId = substr($url, strpos($url, "VideoId=") + 8);
                $videoId = substr($videoId, 0, strpos($videoId, "&"));
                $userId = $_SESSION["userId"];

                $sql = "INSERT INTO comment (VideoId, AccountId, Content, QuestionId) 
                        VALUES (?, ?, ?, ?)";
                        
                stmtExecute($sql, 1, "iisi", $videoId, $userId, $description, $id);

                if(stmtExecute($sql, 1, "iisi", $videoId, $userId, $description, $id)) {
                    header("Location: questions.php?TitleId=$id&Success");
                } else {
                    header("Location: questions.php?TitleId=$id&Failed");
                }
            } else {
                echo "URL is not a valid URL, please link to a video on this website!";
            }

        } else {
            header("Location: questions.php?TitleId=$id");
        }
    } else {
        header("Location: questions.php");
    }
} else {
    header("Location: questions.php");
}


?>