<?php include_once("../assets/components/loginCheck.php") ?>
<?php require_once("../utils/dbconnect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/profile/profile.css">
</head>

<body>
    <?php include_once('../assets/components/header.php') ?>
    <?php include_once("../utils/functions.php") ?>
    <div class="fullPageContent center">
        <div class="contentBlock">
            <div class="profileBlock">
                <h1>Change password</h1>
                <form action="" method="post">

                    <label for="name">Old password</label>
                    <input type="text" name="password" value="">

                    <label for="name">New password</label>
                    <input type="text" name="newpassword" value="">


                    <input class="profileBtn" type="submit" name="update" value="Save profile">
                    
              <?php 
                      if(isset($_POST["update"])){
                        
                        
                        $accountId = $_SESSION['userId'];

                        $query = "SELECT `Password` FROM account WHERE Id = ?";

                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $accountId);

                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $password);
                        mysqli_stmt_store_result($stmt);
                        mysqli_stmt_fetch($stmt);
                        mysqli_stmt_close($stmt);
                        

                        $hashedpassword =  $_POST['password'];
                        if(password_verify($hashedpassword,$password)){                            
                            $newhashedpw =   password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                        $updatequery = "UPDATE account
                        SET `Password`= ?
                        WHERE `Id` = ?";
            
                        $updatestmt = mysqli_prepare($conn, $updatequery);
                        mysqli_stmt_bind_param($updatestmt, 'si', $newhashedpw, $accountId);
            
                        mysqli_stmt_execute($updatestmt);

                        echo "Password succesfully changed!";
                        }else{
                            echo "password incorrect!";
                        }
                        

                    }
              
              ?>
                </form>
              
            </div>
</html>
<?php include_once("../utils/dbclose.php"); ?>