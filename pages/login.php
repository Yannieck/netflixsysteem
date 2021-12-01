<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/login.css">
</head>

<body>
    <div class="background center">
        <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
        <div class="content">
            <h1>Sign up</h1>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <!-- <form action="../index.php" method="post"> -->
                <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>
                <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                <p class="errortext" id="passworderror">Your password must contain between 4 and 8 characters.</p>
                <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                <div class="content1">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Need help?</a>
                </div>

            </form>
            <div class="content2">
                <p>New to Null Pointer Videos? <a href="./createaccount.php">Sign up now</a>.</p>
            </div>
        </div>
    </div>
    <?php
    if (isset($_POST['login'])) {
        if (!empty($_POST['email'])) {
            if (!empty($_POST['password'])) {
                if (isset($_POST['remember'])) {
                    echo "remember";
                } else {
                    header('Location: ./choosemembership.php');
                }
            } else {
                echo
                "<script>
                    document.getElementById('passworderror').style.display = 'block';
                    document.getElementById('email').value = '" . $_POST['email'] . "';
                </script>";
            }
        } else {
            echo
            "<script>
                document.getElementById('emailerror').style.display = 'block';
            </script>";
        }
    }
    ?>

</body>

</html>