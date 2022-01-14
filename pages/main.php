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
                                            account.MembershipName AS Membership,
                                            video.UploadDate AS UploadDate, 
                                            video.Thumbnail AS Thumbnail
                            FROM video, question, account, tag_question, subtag, tag
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND tag_question.QuestionId = video.QuestionId
                                AND tag_question.SubTagID = subtag.Id
                                AND subtag.TagId = tag.Id
                                AND tag.id = ?
                            ORDER BY video.UploadDate;";

                    $tagId = filter_input(INPUT_GET, "tag", FILTER_SANITIZE_NUMBER_INT);
                    $results = stmtExecute($sql, 1, "i", $tagId);
                } else if (isset($_GET['search'])) {
                    $sql = "SELECT DISTINCT video.Id AS VideoId, 
                                            question.title AS QuestionTitle, 
                                            account.Username AS Username, 
                                            account.MembershipName AS Membership,
                                            video.UploadDate AS UploadDate, 
                                            video.File AS File
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id 
                                AND video.AccountId = account.Id
                                AND question.title LIKE CONCAT('%', ?, '%')
                            ORDER BY video.UploadDate;";

                    $search = filter_input(INPUT_GET, "search", FILTER_SANITIZE_SPECIAL_CHARS);
                    $results = stmtExecute($sql, 1, "i", $search);
                } else {
                    // Pak standaard van de video: id, title, accountnaam, upload datum, bestandslocatie
                    $sql = "SELECT DISTINCT video.Id AS VideoId, 
                                            question.title AS QuestionTitle, 
                                            account.Username AS Username, 
                                            account.MembershipName AS Membership,
                                            video.UploadDate AS UploadDate, 
                                            video.Thumbnail AS Thumbnail
                            FROM video, question, account
                            WHERE video.QuestionId = question.Id 
                            AND video.AccountId = account.Id
                            ORDER BY video.UploadDate;";
                    $results = stmtExecute($sql);
                }

                if (is_array($results) && count($results["VideoId"]) > 0) {
                    $numResults = count($results["VideoId"]);
                    for ($i = 0; $i < $numResults; $i += 10) {
                        echo "<div class='vidRow'>";
                        for ($i = 0; $i < $numResults; $i++) {
                            if ($i < 10) {
                                $videoId = $results["VideoId"][$i];
                                $videoPath = $results["Thumbnail"][$i];
                                $videoDate = $results["UploadDate"][$i];
                                $accountName = $results["Username"][$i];
                                $questionTitle = $results["QuestionTitle"][$i];
                                $membershipName = $results["Membership"][$i];

                                // Als er geen thumbnail is geselecteerd, zet er een placeholder afbeelding neer.
                                $fullPath = "../assets/upload/thumbnails/" . $videoPath;
                                if (empty($videoPath)) {
                                    $fullPath = "../assets/img/image_placeholder.png";
                                }

                                // Zorg dat er maar maximaal 10 video's getoond kunnen worden
                                // Voor elke video die is gevonden doe dit:
                    ?>
                                <a href='videopage.php?VideoId=<?php echo $videoId ?>'>
                                    <div class='vidHolder'>
                                        <div class='overflowHidden'>
                                            <!-- De Thumbnail: -->
                                            <img src='<?php echo $fullPath ?>' alt='<?php echo $questionTitle ?>'>

                                            <!-- De video hover die de likes laat zien: -->
                                            <div class='vidHover'>
                                                <div class='vidHoverBot'>
                                                    <p>
                                                        <i class='far fa-thumbs-up'></i>
                                                        <?php echo getLikes(1, $videoId) ?>
                                                        <i class='far fa-thumbs-down'></i>
                                                        <?php echo getLikes(0, $videoId) ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Het element onder de video die de titel, upload datum en de uploader weergeeft -->
                                        <div class='vidInfoHolder'>
                                            <p class='title'><?php echo $questionTitle ?></p>
                                            <div class='otherInfo'>
                                                <p>
                                                    <?php
                                                    if ($membershipName == "Admin") {
                                                    ?><i class='fas fa-check'>&nbsp;</i>
                                                    <?php }
                                                    echo $accountName ?></p>
                                                <p><?php echo calculateDate($videoDate) ?> ago</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                <?php
                            } else {
                                break;
                            }
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