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
    <link rel="stylesheet" href="../assets/styles/mainpage/main.css">
</head>

<body>
    <?php include_once("../assets/components/header.php"); ?>
    <div class="page">
        <?php include_once("../assets/components/aside.php"); ?>
        <div class="pageContent">
            <?php
            // Kijk of er een get is gezet.
            if (isset($_GET['id'])) {
                // Kijk of de get een geldige id waarde heeft.
                if (filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT)) {
                    // Haal de informatie op over het filmpje.
                    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
                    $sql = "SELECT video.Id, question.Title, video.Description, account.Name, video.File, video.UploadDate, question.Id FROM video, account, question WHERE video.Id = ? AND account.Id = video.AccountId AND question.Id = video.QuestionId;";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, 'i', $id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $videoId, $title, $desc, $creator, $filename, $uploadDate, $questionId);
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_fetch($stmt);

                    // Kijk of er een resultaat is.
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        // Laat het filmpje zien.
                        mysqli_stmt_close($stmt)
            ?>
                        <!-- HTML van het video element en de teksten er omheen. -->
                        <div class="largeVideoContainer">
                            <!-- Titel. -->
                            <div class="titleObj">
                                <h2><?php echo $title ?></h2>
                            </div>
                            <!-- Video en de teksten. -->
                            <div class="videoHolder">
                                <!-- De video. -->
                                <video controls autoplay controlslist="nodownload">
                                    <source src="<?php echo "../assets/upload/videos/" . $filename ?>">
                                </video>
                                <!-- De info onder de video. -->
                                <div class="infoHolder">
                                    <!-- Creator tekst en upload datum. -->
                                    <div>
                                        <p>Uploaded by: <span><?php echo $creator ?></span></p>
                                        <p>Uploaded: <span><?php echo calculateDate($uploadDate) ?> ago</span></p>
                                    </div>
                                    <!-- Likes/dislikes. -->
                                    <div>
                                        <?php
                                        // Query om de likes / dislikes op te halen
                                        $sql = "SELECT (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 1), (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 0);";
                                        $stmt = mysqli_prepare($conn, $sql);
                                        mysqli_stmt_bind_param($stmt, 'ii', $videoId, $videoId);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_bind_result($stmt, $likes, $dislikes);
                                        mysqli_stmt_store_result($stmt);
                                        mysqli_stmt_fetch($stmt);
                                        ?>
                                        <!-- De likes / dislike display -->
                                        <p><i class='far fa-thumbs-up'></i>&nbsp;<?php echo $likes ?>&nbsp;&nbsp;<i class="far fa-thumbs-down">&nbsp;<?php echo $dislikes ?></i></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Link naar de orginele vraag. -->
                            <div class="questionLink">
                                <p>View the original question: &nbsp;</p>
                                <a href="questions.php?TitleId=<?php echo $questionId ?>"><?php echo $title ?></a>
                            </div>
                        </div>
            <?php
                    } else {
                        echo "<h2>Error: page not found</h2>";
                    }
                } else {
                    echo "<h2>Error: page not found</h2>";
                }
            } else {
                echo "<h2>Error: page not found</h2>";
            }
            ?>
        </div>
    </div>
</body>

<?php include_once("../utils/dbclose.php"); ?>

</html>