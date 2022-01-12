<?php
include_once("../assets/components/loginCheck.php");
include_once("../utils/dbconnect.php");
include_once("../utils/functions.php");

$showLikes = function($type, $postId) use ($conn)
{
    $sql = "SELECT COUNT(`like`.`type`) FROM `like` WHERE `like`.`type` = ? AND `like`.`VideoId` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ii', $type, $postId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $likes);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_fetch($stmt);
    return $likes;
}
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

                // Pak standaard van de video: id, title, accountnaam, upload datum, bestandslocatie
                $sql = "SELECT DISTINCT video.Id, question.title, account.Username, video.UploadDate, video.File
                        FROM video, question, account
                        WHERE video.QuestionId = question.Id AND video.AccountId = account.Id
                        ORDER BY video.UploadDate;";

                // Als er een get waarde is gezet; als er wordt gefilterd: filter op tag id
                if (isset($_GET['tag'])) {
                    $sql = "SELECT DISTINCT video.Id, question.title, account.Username, video.UploadDate, video.File
                            FROM video, question, account, tag_question, subtag, tag
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND tag_question.QuestionId = video.QuestionId
                                AND tag_question.SubTagID = subtag.Id
                                AND subtag.TagId = tag.Id
                                AND tag.id = ?;";
                } else if (isset($_GET['search'])) {
                    $sql = "SELECT DISTINCT video.Id, question.title, account.Username, video.UploadDate, video.File
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND question.title LIKE CONCAT('%', ?, '%')";
                }
                // Prepare stmt
                $stmt = mysqli_prepare($conn, $sql);

                // Als er wordt gefilterd, zet de ? naar tagId
                if (isset($_GET['tag'])) {
                    $tagId = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_NUMBER_INT);
                    mysqli_stmt_bind_param($stmt, 'i', $tagId);
                } else if (isset($_GET['search'])) {
                    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
                    mysqli_stmt_bind_param($stmt, 's', $search);
                }
                // Standaard database resultaat vergrijgen
                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                mysqli_stmt_bind_result($stmt, $videoId, $questionTitle, $accountName, $videoDate, $videoPath)  or die(mysqli_error($conn));
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                ?>
                    <div class="vidRow">
                        <?php
                        $index = 0;
                        while (mysqli_stmt_fetch($stmt)) {
                            $index++;
                            if ($index < 10) {
                                // Zorg dat er maar maximaal 10 video's getoond kunnen worden
                                // Voor elke video die is gevonden doe dit:
                        ?>
                                <div class='vidHolder' onclick="openVideo(<?php echo $videoId ?>)">
                                    <div class="overflowHidden">
                                        <!-- Het video element: -->
                                        <video>
                                            <source src=<?php echo "../assets/upload/videos/" . $videoPath ?> type="video/mp4">
                                            Your browser does not support HTML video.
                                        </video>
                                        <!-- De video hover die de likes laat zien: -->
                                        <div class="vidHover">
                                            <div class="vidHoverTop">
                                                <a href="#"><i class="far fa-bookmark"></i></a>
                                            </div>
                                            <div class="vidHoverBot">
                                                <p><i class="far fa-thumbs-up"></i>&nbsp;<?php echo $showLikes(1, $videoId); ?>&nbsp;&nbsp;<i class="far fa-thumbs-down"></i>&nbsp;<?php echo $showLikes(0, $videoId); ?></p>
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
                <?php
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
<script>
    // Stukje javascript daar link naar de video waar op is geklikt
    function openVideo(videoId) {
        window.location.href = "./videopage.php?id=" + videoId;
    }
</script>

</html>