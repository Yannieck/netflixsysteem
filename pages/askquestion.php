<?php
include_once("../assets/components/loginCheck.php");
include_once("../utils/functions.php");
include_once("../utils/dbconnect.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/login/login.css">
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/aside/aside.css">
    <link rel="stylesheet" href="../assets/styles/askquestion/askquestion.css">
</head>

<body>
    <?php include_once("../assets/components/header.php"); ?>
    <div class="page">
        <?php include_once("../assets/components/aside.php"); ?>
        <div class="pageContent">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <label for="title">Enter your question title:</label>
                <input type="text" name="title" id="title" placeholder="Title...">
                <label for="text">Enter your question here:</label>
                <textarea name="text" id="text" placeholder="Question..."></textarea>
                <input name="submit" class="button" type="submit" value="Submit">
            </form>
            <!-- <p id="output"> -->
            <?php
            // function stmtExecute($connection, string $sql, int $code, string $ParamChars = NULL, ...$BindParamVars) : ?array
            
            $sql = "INSERT INTO question (Title, AccountId, Content) VALUES (?, ?, ?)";
            stmtExecute($conn, $sql, 1, "sis", $title, $account, $content);


            // echo htmlentities($_POST['title']);
            // echo "<br>";
            // $rawinput = $_POST['text'];
            // if (str_contains($rawinput, "<<") && str_contains($rawinput, ">>")) {
            //     $begin = strpos($rawinput, "<<") + 1;
            //     $end = strpos($rawinput, ">>") - 1;
            //     $len = $end - $begin;
            //     $code = substr($rawinput, $begin + 1, $len);
            //     $otherText = explode($code, $rawinput);
            //     echo htmlentities(substr($otherText[0], 0, -2));
            //     echo "<code>";
            //     echo $code;
            //     echo "</code>";
            //     echo htmlentities(substr($otherText[1], 2, strlen($otherText[1])));
            // } else {
            //     echo htmlentities($rawinput);
            // }

            // if (isset($_POST["submit"])) { 
            //     if (!empty($_POST["title"])) {
            //         // $titleError = test_input($_POST["title"]);
            //     } else { 

            //         echo '<span style="color:red;"> Title is required! </span>';
            //     }
            // }
            ?>
            <!-- </p> -->
        </div>
    </div>
</body>
<?php
include_once("../utils/dbclose.php");
?>
</html>