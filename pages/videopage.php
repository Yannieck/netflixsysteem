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
            if (isset($_GET['VideoId'])) {
                // Kijk of de get een geldige id waarde heeft.
                if (filter_input(INPUT_GET, "VideoId", FILTER_VALIDATE_INT)) {
                    // Haal de informatie op over het filmpje.
                    $id = filter_input(INPUT_GET, "VideoId", FILTER_SANITIZE_NUMBER_INT);
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
                                        $sql = "SELECT (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 1),
                                        (SELECT COUNT(`like`.`Type`) FROM `like`,video WHERE `like`.`VideoId` = video.Id AND video.Id = ? AND `like`.`Type` = 0),
                                        (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 1 AND `like`.`VideoId` = ?),
                                        (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 0 AND `like`.`VideoId` = ?);";

                                        $stmt = mysqli_prepare($conn, $sql);
                                        mysqli_stmt_bind_param($stmt, 'iiii', $videoId, $videoId, $videoId, $videoId);
                                        mysqli_stmt_execute($stmt);
                                        mysqli_stmt_bind_result($stmt, $likes, $dislikes, $likedUsers, $dislikedUsers);
                                        mysqli_stmt_store_result($stmt);
                                        mysqli_stmt_fetch($stmt);
                                        mysqli_stmt_close($stmt);

                                        // Array met alle id's van mensen die de video geliked hebben
                                        $likedUserArr = explode(',', $likedUsers);
                                        $liked = in_array($_SESSION['userId'], $likedUserArr);
                                        $likedStr = $liked ? "fas" : "far";

                                        // Array met alle id's van mensen die de video gedisliked hebben
                                        $dislikedUserArr = explode(',', $dislikedUsers);
                                        $disliked = in_array($_SESSION['userId'], $dislikedUserArr);
                                        $dislikedStr = $disliked ? "fas" : "far";

                                        // Als de video al geliked is, verhoog de type met 2.
                                        // Dit wordt in de functies afgevangen en zorgt ervoor dat de like
                                        // uit het systeem wordt gehaald.
                                        $likeType = $liked ? 3 : 1;
                                        $dislikeType = $disliked ? 2 : 0;
                                        ?>
                                        <!-- De likes / dislike display -->
                                        <!-- Als de like btn is geklikt, zet de get['like'] naar 1 -->
                                        <!-- Als de dislike btn is geklikt, zet de get['like'] naar 0 -->
                                        <a href="?VideoId=<?php echo $videoId ?>&like=<?php echo $likeType ?>">
                                            <i class="<?php echo $likedStr ?> fa-thumbs-up"></i>
                                        </a>
                                        <p><?php echo $likes ?></p>

                                        <a href="?VideoId=<?php echo $videoId ?>&like=<?php echo $dislikeType ?>">
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
                                    <img class="pfp" src="../assets/img/profiles/unknown.png">
                                    <form action="<?php echo $_SERVER['PHP_SELF'] . "?VideoId=" . $videoId ?>" method="POST">
                                        <input type="text" name="commentText" placeholder="Comment...">
                                        <button class="send" type="submit" name="postComment"><i class="far fa-paper-plane"></i></button>
                                    </form>
                                    <p class="errorText commentError" id="commentError">Please enter a comment.</p>
                                </div>
                                <?php
                                // Haal de informatie over de comment op uit de database.
                                $sql = "SELECT comment.Id, comment.Content, comment.CommentDate, comment.QuestionId, account.Username FROM comment, account WHERE comment.VideoId = ? AND comment.AccountId = account.Id";
                                $stmt = mysqli_prepare($conn, $sql);
                                mysqli_stmt_bind_param($stmt, 'i', $videoId);
                                mysqli_stmt_execute($stmt);
                                mysqli_stmt_bind_result($stmt, $commentId, $commentText, $commentTime, $commentQuestion, $commentUser);
                                mysqli_stmt_store_result($stmt);
                                // Als er resultaten zijn, loop door alle resultaten heen.
                                if (mysqli_stmt_num_rows($stmt) > 0) {
                                    while (mysqli_stmt_fetch($stmt)) {
                                        if ($commentQuestion == null) {
                                            // Voor elk resultaat: maar een comment aan in html.
                                ?>
                                            <div class="comment">
                                                <img class="pfp" src="../assets/img/profiles/unknown.png">
                                                <div class="commentText">
                                                    <p class="username"><?php echo $commentUser ?> - <?php echo calculateDate($commentTime) ?> ago</p>
                                                    <p class="text"><span><?php echo $commentText ?><span></p>

                                                    <?php
                                                    $sqlCom = "SELECT (SELECT COUNT(`like`.`Type`) FROM `like`,comment WHERE `like`.`CommentId` = comment.Id AND comment.Id = ? AND `like`.`Type` = 1) as 'likes',
                                                (SELECT COUNT(`like`.`Type`) FROM `like`,comment WHERE `like`.`CommentId` = comment.Id AND comment.Id = ? AND `like`.`Type` = 0) as 'dislikes',
                                                (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 1 AND `like`.`CommentId` = ?) as 'likedAccs',
                                                (SELECT GROUP_CONCAT(`like`.`AccountId`) FROM `like` WHERE `like`.`Type` = 0 AND `like`.`CommentId` = ?) as 'dislikedAccs';";

                                                    // $results = stmtExecute($sql, 1, 'iiii', $videoId, $videoId, $videoId, $videoId);
                                                    // var_dump($result);

                                                    // Haal de informatie over de comment likes en dislikes op uit de database.
                                                    $stmtCom = mysqli_prepare($conn, $sqlCom);
                                                    mysqli_stmt_bind_param($stmtCom, 'iiii', $commentId, $commentId, $commentId, $commentId);
                                                    mysqli_stmt_execute($stmtCom);
                                                    mysqli_stmt_bind_result($stmtCom, $comLikes, $comDislikes, $comLikedUsers, $comDislikedUsers);
                                                    mysqli_stmt_store_result($stmtCom);
                                                    mysqli_stmt_fetch($stmtCom);
                                                    mysqli_stmt_close($stmtCom);

                                                    // Array met alle id's van mensen die de comment geliked hebben.
                                                    $comLikedUserArr = explode(',', $comLikedUsers);
                                                    $comLiked = in_array($_SESSION['userId'], $comLikedUserArr);
                                                    $comLikedStr = $comLiked ? "fas" : "far";

                                                    // Array met alle id's van mensen die de comment gedisliked hebben.
                                                    $comDislikedUserArr = explode(',', $comDislikedUsers);
                                                    $comDisliked = in_array($_SESSION['userId'], $comDislikedUserArr);
                                                    $comDislikedStr = $comDisliked ? "fas" : "far";

                                                    // Als de comment al geliked is, tel bij de type 2 op.
                                                    // Dit zorgt er voor dat een like uit de database wordt gehaalt.
                                                    // Dit wordt afgevangen in de functies addLike en removeLike.
                                                    $comLikeType = $comLiked ? 3 : 1;
                                                    $comDislikeType = $comDisliked ? 2 : 0;
                                                    ?>

                                                    <div class="likes">
                                                        <a href="?VideoId=<?php echo $videoId ?>&comlike=<?php echo $comLikeType ?>">
                                                            <i class="<?php echo $comLikedStr ?> fa-thumbs-up"></i>
                                                        </a>
                                                        <p><?php echo $comLikes ?></p>
                                                        <a href="?VideoId=<?php echo $videoId ?>&comlike=<?php echo $comDislikeType ?>">
                                                            <i class="<?php echo $comDislikedStr ?> fa-thumbs-down"></i>
                                                        </a>
                                                        <p><?php echo $comDislikes ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    }
                                }
                                mysqli_stmt_close($stmt)
                                ?>
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
// === Voeg de geplaatste comment toe aan de database ===
if (isset($_POST['postComment'])) {
    if (!empty($_POST['commentText'])) {
        $comment = filter_input(INPUT_POST, 'commentText', FILTER_SANITIZE_SPECIAL_CHARS);
        $accountId = $_SESSION["userId"];
        $sql = "INSERT INTO comment (VideoId, AccountId, Content) VALUES (?,?,?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'iis', $videoId, $accountId, $comment);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_fetch($stmt);
        header("Location: " . $_SERVER["PHP_SELF"] . "?VideoId=" . $videoId);
    }
}
// === Stop een like in de database ===

$addLike = function ($type, $vidId, $userId, $isVid) use ($conn) {
    // Stop de nieuwe like in de database
    if ($isVid == true) {
        $sql = "INSERT INTO `like` (`AccountId`, `VideoId`, `Type`) VALUES (?,?,?);";
    } else {
        $sql = "INSERT INTO `like` (`AccountId`, `CommentId`, `Type`) VALUES (?,?,?);";
    }

    echo "add like: " . $userId . " - vidId: " . $vidId . " - type: " . $type . "<br>";
    var_dump($isVid);
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iii', $userId, $vidId, $type);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
};

// === Haal een like uit de database ===

$removeLike = function ($type, $vidId, $userId, $isVid) use ($conn) {
    // Haal de like/dislike uit de database
    if ($isVid == true) {
        $sql = "DELETE FROM `like` WHERE `like`.`AccountId`=? AND `like`.`VideoId`=? AND `like`.`Type`=?;";
    } else {
        $sql = "DELETE FROM `like` WHERE `like`.`CommentId`=? AND `like`.`CommentId`=? AND `like`.`Type`=?;";
    }
    $stmt = mysqli_prepare($conn, $sql);
    $type -= 2;
    echo "remove like: " . $userId . " - vidId: " . $vidId . " - type: " . $type . "<br>";
    var_dump($isVid);

    mysqli_stmt_bind_param($stmt, 'iii', $userId, $vidId, $type);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
};

// === Video likes ===

// Als er een get zijn voor "like" en "id", voeg de like toe.
if (isset($_GET['like']) && isset($_GET['VideoId'])) {
    // Zet voor het gemak de waarden in een variabel.
    $vidId = $_GET['VideoId'];
    $type = $_GET['like'];
    $userId = $_SESSION['userId'];

    // Roep de functie op om de like in de database te zetten.
    if ($type == 0) {
        // like
        $removeLike(3, $vidId, $userId, true);
        $addLike($type, $vidId, $userId, true);
    } else if ($type == 1) {
        // dislike
        $removeLike(2, $vidId, $userId, true);
        $addLike($type, $vidId, $userId, true);
    } else if ($type == 2 || $type == 3) {
        $removeLike($type, $vidId, $userId, true);
    }
    // Haal de like waarde weer uit de get.
    header("Location: " . $_SERVER['PHP_SELF'] . "?VideoId=" . $vidId);
}

// === Comment likes ===

// Als er een get zijn voor "comLike" en "id", voeg de like toe.
if (isset($_GET['comlike']) && isset($_GET['VideoId'])) {
    // Zet voor het gemak de waarden in een variabel.
    $vidId = $_GET['VideoId'];
    $type = $_GET['comlike'];
    $userId = $_SESSION['userId'];

    // Roep de functie op om de like in de database te zetten.
    if ($type == 0) {
        // like
        echo "like de comment <br>";
        $removeLike(3, $vidId, $userId, false);
        $addLike($type, $vidId, $userId, false);
    } else if ($type == 1) {
        // dislike
        echo "dislike de comment <br>";
        $removeLike(2, $vidId, $userId, false);
        $addLike($type, $vidId, $userId, false);
    } else if ($type == 2 || $type == 3) {
        echo "delete <br>";
        echo $type;
        $removeLike($type, $vidId, $userId, false);
    }
    // Haal de like waarde weer uit de get.
    header("Location: " . $_SERVER['PHP_SELF'] . "?VideoId=" . $vidId);
}
?>

<?php
include_once("../utils/dbclose.php");
ob_end_flush();
?>

</html>