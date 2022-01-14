<?php include_once("../assets/components/loginCheck.php") ?>
<?php require_once("../utils/dbconnect.php");
ob_start();
?>
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

    $sql = "SELECT COUNT(DISTINCT account.Id) AS AccountAMT, 
                    COUNT(DISTINCT question.Id) AS QuestionAMT, 
                    COUNT(DISTINCT video.Id) AS VideoAMT, 
                    COUNT(DISTINCT comment.Id) AS CommentAMT,
                    (SELECT COUNT(account.MembershipName) 
                        FROM account
                        WHERE account.MembershipName = 'Junior') AS JuniorAMT,
                    (SELECT COUNT(account.MembershipName) 
                    FROM account 
                    WHERE account.MembershipName = 'Senior') AS SeniorAMT
                FROM account, question, video, comment;";

    $results = stmtExecute($sql, 0);
    $accountAmt = $results["AccountAMT"][0];
    $questionAmt = $results["QuestionAMT"][0];
    $videoAmt = $results["VideoAMT"][0];
    $commentAmt = $results["CommentAMT"][0];
    $juniorAmt = $results["JuniorAMT"][0];
    $seniorAmt = $results["SeniorAMT"][0];

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
                        $imgDir = $dir . "/" . $file
                    ?>
                        <div class="imgElement">
                            <img src=<?php echo $imgDir ?> alt="">
                            <div class="imgInfoHolder">
                                <div class="imgText">
                                    <p>Name: <?php echo $name ?></p>
                                    <p>Email: <?php echo $email ?></p>
                                </div>
                                <div class="imgBtns">
                                    <a href="?decl=<?php echo $imgDir ?>" class="decl">Decline</a>
                                    <a href="?acc=<?php echo $email ?>&dir=<?php echo $imgDir ?>" class="accept">Accept</a>
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
if (isset($_GET['decl'])) {
    $dir = filter_input(INPUT_GET, 'decl', FILTER_SANITIZE_SPECIAL_CHARS);
    unlink($dir);
    $page = $_SERVER['PHP_SELF'] . "?Notifications=hidden";
    header("Location: $page");
}
if (isset($_GET['acc'])) {
    $email = filter_input(INPUT_GET, 'acc', FILTER_SANITIZE_EMAIL);
    $sql = "UPDATE account SET account.MembershipName = 'Prof' WHERE account.Email = ?;";
    $result = stmtExecute($sql, 1, 's', $email);

    $dir = filter_input(INPUT_GET, 'dir', FILTER_SANITIZE_SPECIAL_CHARS);
    unlink($dir);
    $page = $_SERVER['PHP_SELF'] . "?Notifications=hidden";

    debug($result);
    header("Location: $page");
}
ob_end_flush();
?>

</html>