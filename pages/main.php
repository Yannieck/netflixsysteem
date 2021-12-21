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
            <?php include_once("../assets/components/taglist.php"); ?>
            <div class="videoContainer">
                <div class="vidRow">
                    <?php
                    // $files = array_diff(scandir("../assets/videos"), array('..', '.'));

                    $sql = "SELECT video.Id, question.title, account.Name, video.UploadDate, video.File
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id AND video.AccountId = account.Id;";

                    if(isset($_GET['tag'])) {
                        $sql = "SELECT video.Id, question.title, account.Name, video.UploadDate, video.File
                                FROM video, question, account, tag_question, subtag, tag
                                WHERE video.QuestionId = question.Id 
                                    AND video.AccountId = account.Id
                                    AND tag_question.QuestionId = video.QuestionId
                                    AND tag_question.SubTagID = subtag.Id
                                    AND subtag.TagId = tag.Id
                                    AND tag.id = ?;";
                    }                    

                    $stmt = mysqli_prepare($conn, $sql);
                    
                    if (isset($_GET['tag'])) {
                        $tagId = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_NUMBER_INT);
                        mysqli_stmt_bind_param($stmt, 'i', $tagId);
                    }

                    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                    mysqli_stmt_bind_result($stmt, $videoId, $questionTitle, $accountName, $videoDate, $videoPath)  or die(mysqli_error($conn));
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        while (mysqli_stmt_fetch($stmt)) {
                    ?>
                            <div class='vidHolder' onclick="openVideo(<?php echo $videoId ?>)">
                                <div class="overflowHidden">
                                    <video>
                                        <source src=<?php echo "../assets/videos/" . $videoPath ?> type="video/mp4">
                                        Your browser does not support HTML video.
                                    </video>
                                    <div class="vidHover">
                                        <div class="vidHoverTop">
                                            <a href="#"><i class="far fa-bookmark"></i></a>
                                        </div>
                                        <!-- <div class="vidHoverBot">
                                            <p>PLACEHOLDER</p>
                                        </div> -->
                                    </div>
                                </div>
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
    function openVideo(videoId) {
        console.log(videoId);
    }
</script>

</html>