<?php 
include_once("../assets/components/loginCheck.php");
require_once("../utils/functions.php"); 
 
$accountId = $_SESSION['userId'];

$sql = "SELECT Username FROM `account` WHERE `Id` = ?";

$results = stmtExecute($sql, 1, 'i', $accountId);
$name = $results["Username"][0];
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../assets/styles/deleteprofile/deleteprofile.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>
<body>
    <div class="container">
        <div class="contentBlock">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="textBlock">
                    <h2>Are you sure you want to delete your account?</h2>
                    <h4 style="color: #FF5959">This can't be undone!</h4>
                </div>
                <div class="inputBlock">
                    <h3>Type <span>@<?php echo $name?></span> to delete your account</h3>
                    <input type="text" name="username" placeholder="Type here...">
                </div>
                <div class="buttons">
                    <input type="submit" name="submit" value="Delete profile">
                    <a href="profile.php">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <?php

        if(isset($_POST["submit"])){
            echo $name;
            if(!empty($_POST["username"])){
                if($_POST["username"] == $name){
                
                    $sql = "DELETE FROM account WHERE Id = ?";

                    stmtExecute($sql, 1, 'i', $accountId);

                    header("Location: logout.php");
                }else{
                    echo "Wrong username.";
                }
            }else{
                echo "Fill in username please.";
            }
        }
    ?>
</body>
