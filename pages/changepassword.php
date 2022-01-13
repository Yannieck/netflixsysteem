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

                        $sql = "SELECT Password FROM account WHERE Id = ?";

                        $result = stmtExecute($sql, 1, "i", $accountId);
                        $password = $result["Password"][0];

                        $hashedpassword =  $_POST['password'];
                        if(password_verify($hashedpassword,$password)){                            
                            $newhashedpw =   password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                            $sql = "UPDATE account
                                            SET Password = ?
                                            WHERE Id = ?";
                
                            stmtExecute($sql, 1, 'si', $newhashedpw, $accountId);

                            echo "<p>Password succesfully changed!</p>";
                        }else{
                            echo "<p>password incorrect!</p>";
                        }
                        

                    }
              
              ?>
                </form>
              
            </div>
</html>
<?php include_once("../utils/dbclose.php"); ?>