<?php require_once("../utils/dbconnect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/login/login.css">
</head>

<body>
    <div class="background center">
        <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
        <div class="content width-1">
            <h1>Sign in</h1>
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <!-- Email veld -->
                <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>

                <!-- Wachtwoord veld -->
                <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                <p class="errortext" id="passworderror">Your password must contain more then 4 characters.</p>

                <!-- Submit -->
                <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                <!-- Remember me + help -->
                <div class="content1">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="./loginhelp.php">Need help?</a>
                </div>
            </form>
            <!-- Sign up link -->
            <div class="content2">
                <p>New to Null Pointer Videos? <a href="./choosemembership.php">Sign up now</a>.</p>
            </div>
        </div>
    </div>
    <?php
    // Form input valideren
    if (isset($_POST['login'])) {
        if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            if (($password = $_POST['password']) && strlen($_POST['password']) >= 4) {
                $remember = isset($_POST['remember']) == 1 ? 1 : 0;
                
                // Database connectie
                $query = "SELECT `Password`,`Id` FROM `account` WHERE Email = ?";

                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $email);

                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                mysqli_stmt_bind_result($stmt, $passResult, $id) or die(mysqli_error($conn));
                mysqli_stmt_store_result($stmt);

                mysqli_stmt_fetch($stmt);

                if (password_verify($_POST['password'], $passResult)) {
                    header("Location: ./loginredirect.php?rem={$remember}&id={$id}");
                } else {
                    echo "ERROR: Password does not match emailadress.";
                }
                mysqli_stmt_close($stmt);
            } else {
                // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                // en het email veld weer invult
                echo
                "<script>
                    document.getElementById('passworderror').style.display = 'block';
                    document.getElementById('email').value = '" . $_POST['email'] . "';
                </script>";
            }
        } else {
            // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
            echo
            "<script>
                document.getElementById('emailerror').style.display = 'block';
            </script>";
        }
    }
    ?>

</body>

</html>
<?php include_once("../utils/dbclose.php"); ?>