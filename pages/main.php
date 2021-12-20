<?php
include_once("../assets/components/loginCheck.php");
include_once("../utils/dbconnect.php");
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

                    $sql = "SELECT video.Id, question.title, account.Name, video.Description, video.UploadDate, video.File
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id AND video.AccountId = account.Id;";

                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                    mysqli_stmt_bind_result($stmt, $videoId, $questionTitle, $accountName, $videoDesc, $videoDate, $videoPath)  or die(mysqli_error($conn));
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        while (mysqli_stmt_fetch($stmt)) {
                    ?>
                            <div class='vidHolder'>
                                <div class="overflowHidden">
                                    <video>
                                        <source src=<?php echo "../assets/videos/" . $videoPath ?> type="video/mp4">
                                        Your browser does not support HTML video.
                                    </video>
                                    <div class="vidHover">
                                        <div class="vidHoverTop">
                                            <i class="far fa-bookmark"></i>
                                        </div>
                                        <div class="vidHoverBot">
                                            <p><?php echo $questionTitle ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="titleHolder">
                                    <p class="title"><?php echo $questionTitle ?></p>
                                    <p class="uploader"><?php echo $accountName ?></p>
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

</html>