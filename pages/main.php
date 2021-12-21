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
    <div class="flex">
        <?php include_once("../assets/components/aside.php"); ?>
        <div class="pageContent">
            <!-- Include de tag lijst boven aan het scherm -->
            <?php include_once("../assets/components/taglist.php"); ?>
            <div class="videoContainer">
                <div class="vidRow">
                    <?php

                    // Pak standaard van de video: id, title, accountnaam, upload datum, bestandslocatie, aantal likes, aantal dislike.
                    $sql = "SELECT video.Id, question.title, account.Name, video.UploadDate, video.File, (SELECT COUNT(`like`.`Type`) FROM `like`,account WHERE `like`.`Type` = 1) as 'Likes', (SELECT COUNT(`like`.`Type`) FROM `like`,account WHERE `like`.`Type` = 0) as 'Dislike'
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id AND video.AccountId = account.Id
                            ORDER BY video.UploadDate;";

                    // Als er een get waarde is gezet; als er wordt gefilterd: filter op tag id
                    if (isset($_GET['tag'])) {
                        $sql = "SELECT video.Id, question.title, account.Name, video.UploadDate, video.File
                                FROM video, question, account, tag_question, subtag, tag
                                WHERE video.QuestionId = question.Id 
                                    AND video.AccountId = account.Id
                                    AND tag_question.QuestionId = video.QuestionId
                                    AND tag_question.SubTagID = subtag.Id
                                    AND subtag.TagId = tag.Id
                                    AND tag.id = ?;";
                    }

                    // Prepare stmt
                    $stmt = mysqli_prepare($conn, $sql);

                    // Als er wordt gefilterd, zet de ? naar tagId
                    if (isset($_GET['tag'])) {
                        $tagId = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_NUMBER_INT);
                        mysqli_stmt_bind_param($stmt, 'i', $tagId);
                    }

                    // Standaard database resultaat vergrijgen
                    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                    mysqli_stmt_bind_result($stmt, $videoId, $questionTitle, $accountName, $videoDate, $videoPath, $likes, $dislikes)  or die(mysqli_error($conn));
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        while (mysqli_stmt_fetch($stmt)) {
                            // Voor elke video die is gevonden doe dit:
                    ?>
                            <div class='vidHolder' onclick="openVideo(<?php echo $videoId ?>)">
                                <div class="overflowHidden">
                                    <!-- Het video element: -->
                                    <video>
                                        <source src=<?php echo "../assets/videos/" . $videoPath ?> type="video/mp4">
                                        Your browser does not support HTML video.
                                    </video>
                                    <!-- De video hover die de likes laat zien: -->
                                    <div class="vidHover">
                                        <div class="vidHoverTop">
                                            <a href="#"><i class="far fa-bookmark"></i></a>
                                        </div>
                                        <div class="vidHoverBot">
                                            <p><i class="far fa-thumbs-up"></i>&nbsp;<?php echo $likes ?>&nbsp;&nbsp;<i class="far fa-thumbs-down">&nbsp;<?php echo $dislikes ?></i></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Het element onder de video die de titel, upload datum en de uploader weergeeft -->
                                <div class="vidInfoHolder">
                                    <p class="title"><?php echo $questionTitle ?></p>
                                    <div class="otherInfo">
                                        <p><?php echo $accountName ?></p>
                                        <p><?php echo calculateDate($videoDate) ?> ago</p>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Stukje javascript daar link naar de video waar op is geklikt
    function openVideo(videoId) {
        console.log(videoId);
    }
</script>

</html>