<?php
include_once("../assets/components/loginCheck.php");
include_once("../utils/dbconnect.php");
include_once("../utils/functions.php");
ob_start();
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
                    $sql = "SELECT video.Id, question.Title, video.Description, account.Username, video.File, video.UploadDate, question.Id FROM video, account, question WHERE video.Id = ? AND account.Id = video.AccountId AND question.Id = video.QuestionId;";
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
                                <video controls controlslist="nodownload">
                                    <!--autoplay-->
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
                                    <div class="likes">
                                        <?php
                                        // Query om de likes / dislikes op te halen
                                        $sql = "SELECT (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 1), (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 0), (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 1 AND `like`.`VideoId` = ?), (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 0 AND `like`.`VideoId` = ?);";
                                        $stmt = mysqli_prepare($conn, $sql);
                                        mysqli_stmt_bind_param($stmt, 'iiii', $videoId, $videoId, $videoId, $videoId);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_bind_result($stmt, $likes, $dislikes, $likedUsers, $dislikedUsers);
                                        mysqli_stmt_store_result($stmt);
                                        mysqli_stmt_fetch($stmt);
                                        mysqli_stmt_close($stmt);

                                        // Array met alle id's van mensen die geliked hebben
                                        $likedUserArr = explode(',', $likedUsers);
                                        $liked = in_array($_SESSION['userId'], $likedUserArr);
                                        $likedStr = $liked ? "fas" : "far";

                                        // Array met alle id's van mensen die gedisliked hebben
                                        $dislikedUserArr = explode(',', $dislikedUsers);
                                        $disliked = in_array($_SESSION['userId'], $dislikedUserArr);
                                        $dislikedStr = $disliked ? "fas" : "far";

                                        $likeType = $liked ? 3 : 1;
                                        $dislikeType = $disliked ? 2 : 0;
                                        ?>
                                        <!-- De likes / dislike display -->
                                        <!-- Als de like btn is geklikt, zet de get['like'] naar 1 -->
                                        <!-- Als de dislike btn is geklikt, zet de get['like'] naar 0 -->
                                        <a href="?id=<?php echo $videoId ?>&like=<?php echo $likeType ?>">
                                            <i class="<?php echo $likedStr ?> fa-thumbs-up"></i>
                                        </a>
                                        <p><?php echo $likes ?></p>

                                        <a href="?id=<?php echo $videoId ?>&like=<?php echo $dislikeType ?>">
                                            <i class="<?php echo $dislikedStr ?> fa-thumbs-down"></i>
                                        </a>
                                        <p><?php echo $dislikes ?></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Link naar de orginele vraag. -->
                            <div class="questionLink">
                                <p>View the original question: &nbsp;</p>
                                <a href="./questions.php?TitleId=<?php echo $questionId ?>"><?php echo $title ?></a>
                            </div>
                            <!-- Comments -->
                            <div class="commentContainer">
                                <h3>Comments:</h3>
                                <div class="postComment">
                                    <img class="pfp" src="../assets/img/image_placeholder.png">
                                    <form action="<?php echo $_SERVER['PHP_SELF']."?id=".$videoId ?>" method="POST">
                                        <input type="text" name="commentText" placeholder="Comment...">
                                        <button class="send" type="submit" name="postComment"><i class="far fa-paper-plane"></i></button>
                                    </form>
                                </div>
                                <?php
                                $sql = "SELECT comment.Content, comment.CommentDate, account.Username FROM comment, account WHERE comment.VideoId = ? AND comment.AccountId = account.Id";
                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, 'i', $videoId);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_bind_result($stmt, $commentText, $commentTime, $commentUser);
                                mysqli_stmt_store_result($stmt);
                                if (mysqli_stmt_num_rows($stmt) > 0) {
                                    while (mysqli_stmt_fetch($stmt)) {
                                ?>
                                        <div class="comment">
                                            <img class="pfp" src="../assets/img/image_placeholder.png">
                                            <div class="commentText">
                                                <p class="username"><?php echo $commentUser ?> - <?php echo calculateDate($commentTime) ?> ago</p>
                                                <p class="text"><span><?php echo $commentText ?><span></p>
                                            </div>
                                        </div>
                                <?php }
                                } ?>
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

<?php
if (isset($_POST['postComment'])) {
    $comment = filter_input(INPUT_POST, 'commentText', FILTER_SANITIZE_SPECIAL_CHARS);
    $accountId = $_SESSION["userId"];
    $sql = "INSERT INTO comment (VideoId, AccountId, Content) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iis', $videoId, $accountId, $comment);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_fetch($stmt);
    header("Location: ".$_SERVER["PHP_SELF"]."?id=".$videoId);
}

$addLike = function ($type, $vidId, $userId) use ($conn) {
    // Stop de nieuwe like in de database
    $sql = "INSERT INTO `like` (`AccountId`, `VideoId`, `Type`) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $vidId, $type);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
};
$removeLike = function ($type, $vidId, $userId) use ($conn) {
    // Haal de like/dislike uit de database
    $sql = "DELETE FROM `like` WHERE `like`.`AccountId`=? AND `like`.`VideoId`=? AND `like`.`Type`=?;";
    $stmt = mysqli_prepare($conn, $sql);
    $type -= 2;
    echo "remove account: " . $userId . " - vidId: " . $vidId . " - type: " . $type . "<br>";
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $vidId, $type);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
};

// Als er een get zijn voor "like" en "id", voeg de like toe.
if (isset($_GET['like']) && isset($_GET['id'])) {
    // Zet voor het gemak de waarden in een variabel.
    $vidId = $_GET['id'];
    $type = $_GET['like'];
    $userId = $_SESSION['userId'];

    // Roep de functie op om de like in de database te zetten.
    if ($type == 0) {
        // like
        $removeLike(3, $vidId, $userId);
        $addLike($type, $vidId, $userId);
    } else if ($type == 1) {
        // dislike
        $removeLike(2, $vidId, $userId);
        $addLike($type, $vidId, $userId);
    } else if ($type == 2 || $type == 3) {
        $removeLike($type, $vidId, $userId);
    }
    // Haal de like waarde weer uit de get.
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $vidId);
}
?>

<?php
include_once("../utils/dbclose.php");
ob_end_flush();
?>

</html>