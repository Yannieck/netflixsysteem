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
                <h1>Profile</h1>
                <form action="profile.php" method="post">
                    <?php
                    $accountId = $_SESSION['userId'];

                    $query = "SELECT `Name`, `Username`, `Email` FROM account WHERE Id = ?";

                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'i', $accountId);

                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $name, $username, $email);
                    mysqli_stmt_store_result($stmt);

                    if (mysqli_stmt_num_rows($stmt)) {
                        while (mysqli_stmt_fetch($stmt)) {
                    ?>
                            <label for="name">Name</label>
                            <input type="text" name="name" value="<?php echo $name; ?>">

                            <label for="name">Username</label>
                            <input type="text" name="username" value="<?php echo $username; ?>">

                            <label for="name">Email</label>
                            <input type="email" name="email" value="<?php echo $email; ?>">

                    <?php
                        }
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                    <input class="profileBtn" type="submit" name="update" value="Save profile">
                    
              <?php 
                      if(isset($_POST["update"])){
                        echo "test";
                        $updatequery = "UPDATE account
                        SET `Name`= ?, `Username` = ?, `Email` = ?
                        WHERE `Id` = ?";
            
                        $updatestmt = mysqli_prepare($conn, $updatequery);
                        mysqli_stmt_bind_param($updatestmt, 'sssi', $_POST["name"], $_POST["username"],$_POST["email"], $accountId);
            
                        mysqli_stmt_execute($updatestmt);
                    }
              
              ?>
                </form>
              
                <div class="buttons">
                    <!-- <a class="profileBtn deleteBtn" href="deleteprofile.php">Delete profile</a> -->
                    <button class="profileBtn deleteBtn" onclick="window.location.href = './deleteprofile.php';">Delete profile</button>
                    <button class="profileBtn deleteBtn" onclick="window.location.href = './changepassword.php';">Change password</button>
                </div>
            </div>
            <div class="membershipBlock">
                <h1>Select membership</h1>
                <table>
                    <tr>
                        <td></td>
                        <td>
                            <h2>Junior</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>Monthly price</td>
                        <td>&#8364;9,99</td>
                    </tr>
                    <tr>
                        <td>HD available</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>4K available</td>
                        <td><i class="fas fa-times"></i></td>
                    </tr>
                    <tr>
                        <td>Watch on your laptop or TV</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Unlimited videos</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                    <tr>
                        <td>Cancel anytime</td>
                        <td><i class="fas fa-check"></i></td>
                    </tr>
                </table>
                <a href="#" class="button">Upgrade membership</a>
                <!-- <button onclick="upgradeMembership()">Upgrade membership</button> -->
            </div>
        </div>
    </div>
</body>
<script>
    function upgradeMembership() {

    }
</script>

</html>
<?php include_once("../utils/dbclose.php"); ?>