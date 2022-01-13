<?php include_once("../assets/components/loginCheck.php") ?>
<?php require_once("../utils/dbconnect.php"); ?>
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
                    <h3>Type @accountname to delete your account</h3>
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
        $accountId = $_SESSION['userId'];

        $query = "SELECT Username FROM `account` WHERE `Id` = ?";

        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $accountId);

        
        mysqli_stmt_bind_result($stmt, $name);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if(isset($_POST["submit"])){
            echo $name;
            if(!empty($_POST["username"])){
                if($_POST["username"] == $name){
                
                    $delquery = "DELETE FROM account WHERE Id = ?";

                    $stmt = mysqli_prepare($conn, $delquery);
                    mysqli_stmt_bind_param($stmt, 'i', $accountId);

                    mysqli_stmt_execute($stmt);

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
