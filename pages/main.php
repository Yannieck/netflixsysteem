<?php
include_once("../assets/components/loginCheck.php");
include_once("../utils/dbconnect.php");
include_once("../utils/functions.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/aside/aside.css">
    <link rel="stylesheet" href="../assets/styles/mainpage/taglist.css">
    <link rel="stylesheet" href="../assets/styles/mainpage/main.css">
</head>

<body>
    <?php include_once("../assets/components/header.php"); ?>
    <div class="page">
        <?php include_once("../assets/components/aside.php"); ?>
        <div class="pageContent">
            <!-- Include de tag lijst boven aan het scherm -->
            <?php include_once("../assets/components/taglist.php"); ?>
            <div class="videoContainer">
                <?php
                // Als er gezocht wordt naar waarden, zorg dat er een knop komt die het zoeken annuleerdt
                if (isset($_GET['search'])) {
                ?>
                    <a class="button" href="./main.php">Cancel search
                        <i class="hoverCross fas fa-times"></i>
                    </a>
                <?php
                }
                // ===== Haal video's uit de database =====

                // Als er een get waarde is gezet; als er wordt gefilterd: filter op tag id
                if (isset($_GET['tag'])) {
                    $sql = "SELECT DISTINCT video.Id AS VideoId, 
                                            question.title AS QuestionTitle, 
                                            account.Username AS Username, 
                                            video.UploadDate AS UploadDate, 
                                            video.Thumbnail AS Thumbnail
                            FROM video, question, account, tag_question, subtag, tag
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND tag_question.QuestionId = video.QuestionId
                                AND tag_question.SubTagID = subtag.Id
                                AND subtag.TagId = tag.Id
                                AND tag.id = ?;"; 
                    
                    $tagId = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_NUMBER_INT);
                    $results = stmtExecute($sql, 1, "i", $tagId);
                } else if (isset($_GET['search'])) {
                    $sql = "SELECT DISTINCT video.Id AS VideoId, 
                                            question.title AS QuestionTitle, 
                                            account.Username AS Username, 
                                            video.UploadDate AS UploadDate, 
                                            video.File AS File
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND question.title LIKE CONCAT('%', ?, '%')";
                                
                    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
                    $results = stmtExecute($sql, 1, "i", $search);
                } else {
                    // Pak standaard van de video: id, title, accountnaam, upload datum, bestandslocatie
                    $sql = "SELECT DISTINCT video.Id AS VideoId, 
                                            question.title AS QuestionTitle, 
                                            account.Username AS Username, 
                                            video.UploadDate AS UploadDate, 
                                            video.Thumbnail AS Thumbnail
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id AND video.AccountId = account.Id
                            ORDER BY video.UploadDate;";
                    $results = stmtExecute($sql);
                }

                if (is_array($results) && count($results["VideoId"]) > 0) {
                    echo "<div class='vidRow'>";
                        for($i = 0; $i < count($results["VideoId"]); $i++) {
                            if ($i < 10) {
                                $videoId = $results["VideoId"][$i];
                                $videoPath = $results["Thumbnail"][$i];
                                $videoDate = $results["UploadDate"][$i];
                                $accountName = $results["Username"][$i];
                                $questionTitle = $results["QuestionTitle"][$i];
                                // Zorg dat er maar maximaal 10 video's getoond kunnen worden
                                // Voor elke video die is gevonden doe dit:
                                
                                echo "<a href='videopage.php?VideoId=$videoId'>
                                        <div class='vidHolder'>
                                            <div class='overflowHidden'>
                                                <!-- De Thumbnail: -->
                                                <img src='../assets/upload/thumbnails/$videoPath' alt='$questionTitle'>

                                                <!-- De video hover die de likes laat zien: -->
                                                <div class='vidHover'>
                                                    <!-- <div class='vidHoverTop'>
                                                        <a href='#'><i class='far fa-bookmark'></i></a>
                                                    </div> -->
                                                    <div class='vidHoverBot'>
                                                        <p>
                                                            <i class='far fa-thumbs-up'></i>
                                                            &nbsp; ".getLikes(1, $videoId)."&nbsp;&nbsp;
                                                            <i class='far fa-thumbs-down'></i>
                                                            &nbsp; ".getLikes(0, $videoId)."
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Het element onder de video die de titel, upload datum en de uploader weergeeft -->
                                            <div class='vidInfoHolder'>
                                                <p class='title'>$questionTitle</p>
                                                <div class='otherInfo'>
                                                    <p>$accountName</p>
                                                    <p>".calculateDate($videoDate)." ago</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>";

                            } else {
                                break;
                            }
                        }
                    echo "</div>";
                } else {
                    // Als er een get is met tag, laat de tag error zien,
                    // Is er een get met search, laat de search error zien
                    if (isset($_GET['tag'])) {
                        echo "<h2>No results were found with this tag.</h2>";
                    } else if (isset($_GET['search'])) {
                        echo "<h2>No results were found that match this description.</h3>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>