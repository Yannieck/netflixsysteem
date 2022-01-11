<?php include_once("../assets/components/loginCheck.php") ?>
<?php require_once("../utils/dbconnect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/adminpanel/adminpanel.css">
</head>

<body>
    <?php
    include_once('../assets/components/header.php');
    include_once("../utils/functions.php");

    $sql = "SELECT COUNT(DISTINCT account.Id), COUNT(DISTINCT question.Id), COUNT(DISTINCT video.Id), COUNT(DISTINCT comment.Id),
            (SELECT COUNT(membership.Name) FROM membership, account WHERE account.MembershipName = membership.Name AND membership.Name = 'Junior'),
            (SELECT COUNT(membership.Name) FROM membership, account WHERE account.MembershipName = membership.Name AND membership.Name = 'Senior')
            FROM account, question, video, comment";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $accountAmt, $questionAmt, $videoAmt, $commentAmt, $juniorAmt, $seniorAmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);

    $moneyEarned = ($juniorAmt * 9.99) + ($seniorAmt * 14.99);
    ?>
    <div class="fullPageContent center">
        <div class="contentBlock">
            <div class="content leftBlock">
                <h1>Statistics</h1>
                <div class="section">
                    <h2>Post info</h2>
                    <p>Amount of questions asked: <?php echo $questionAmt ?></p>
                    <p>Amount of questions answered: <?php echo $videoAmt ?></p>
                    <p>Amount of comments posted: <?php echo $commentAmt ?></p>
                </div>
                <div class="section">
                    <h2>Account info</h2>
                    <p>Amount of accounts: <?php echo $accountAmt ?></p>
                    <p>Amount of money earned: &euro;<?php echo $moneyEarned ?></p>
                    <p>Amount of junior accounts: <?php echo $juniorAmt ?></p>
                    <p>Amount of senior accounts: <?php echo $seniorAmt ?></p>
                </div>
            </div>
            <div class="content rightBlock">
                <h1>Verification requests</h1>
                <div class="imgHolder">
                    <?php
                    $dir = "../assets/upload/verify-request";
                    $files = array_diff(scandir($dir), array('..', '.'));
                    foreach ($files as $file) {
                        $name = explode("_", $file)[1];
                        $email = str_replace(".png", "", str_replace(".jpg", "", str_replace(".jpeg", "", explode("_", $file)[2])));
                    ?>
                        <div class="imgElement">
                            <img src=<?php echo $dir . "/" . $file ?> alt="">
                            <div class="imgInfoHolder">
                                <div class="imgText">
                                    <p>Name: <?php echo $name ?></p>
                                    <p>Email: <?php echo $email ?></p>
                                </div>
                                <div class="imgBtns">
                                    <a href="" class="decl">Decline</a>
                                    <a href="" class="accept">Accept</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
<?php

?>

</html>
<?php include_once("../utils/dbclose.php"); ?>