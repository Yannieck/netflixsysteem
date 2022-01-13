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
                <!-- <p class="errortext" id="titelerror" style="display: block;">Please enter a mooie titel</p>  -->
                <label for="text">Enter your question here:</label>
                <textarea name="text" id="text" placeholder="Question..."></textarea>
                <label for="tag">Enter your tag here:</label>
                <select name="tag" id="tag">
                    <option value=1>Python</option>
                    <option value=2>Java</option>
                    <option value=3>C++</option>
                    <option value=4>CSS</option>
                    <option value=5>HTML</option>
                    <option value=6>PHP</option>
                    <option value=7>Javascript</option>
                    <option value=8>C#</option>
                    <option value=9>android</option>
                    <option value=10>apple</option>
                    <label for="subtag"> Enter optional tags here...</label>
                    <textarea name="subtag" id="subtag" Placeholder="enter optional tag here"></textarea>
                    <input name="submit" class="button" type="submit" value="submit">
            </form>

            <?php

            if (isset($_POST["submit"])) {
                $title = $_POST["title"];
                $account = $_SESSION["userId"];
                $content = $_POST["text"];
                $tag = $_POST["tag"];
                if (isset($_POST["tag"])) {
                    $sql = "SELECT Category FROM tag WHERE Id=?";
                    $catogory = stmtExecute($sql, 1, "i", $tag);
                }
                $sql = "INSERT INTO question (Title, AccountId, Content) VALUES (?, ?, ?)";
                stmtExecute($sql, 1, "sis", $title, $account, $content);
                if (!empty($_POST["subtag"])) {
                    $subCatergory = $_POST["subtag"];
                    $sql = "INSERT INTO subtag (SubCategory, TagId) VALUES (?, ?)";
                    stmtExecute($sql, 1, "si", $subCatergory, $tag);
                    $sql = "SELECT Id FROM subtag WHERE SubCategory = ? AND TagId=?";
                    $subtagId=stmtExecute($sql, 1, "si", $subCatergory, $tag);
                    $sql = "SELECT Id FROM question WHERE Title = ? AND AccountId=?";
                    $questionId=stmtExecute($sql, 1, "si", $title, $account);
                    debug($subtagId);
                    $sql = "INSERT INTO tag_question (SubTagId, QuestionId) VALUES (?, ?)";
                    stmtExecute($sql, 1, "ii", $subtagId["Id"][0], $questionId["Id"][0]);
                    
                }

               
            }


        //    <?php echo htmlentities($_SERVER['PHP_SELF']); 


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
            //         $titleError = test_input($_POST["title"]);
            //     } else { 

            //         echo '<span style="color:red;"> Title is required! </span>';
            //     }
            // }
            // 
            ?>
            <!-- </p> -->
        </div>
    </div>
</body>
<?php
include_once("../utils/dbclose.php");
?>

</html>