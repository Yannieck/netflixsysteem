<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/login/login.css">
</head>

<body>
    <div class="background center">
        <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
        <div class="content">
            <h1>Sign in</h1>
            <form action="../index.php" method="post">
                <!-- Email veld + error -->
                <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>

                <!-- 1e wachtwoord veld + error -->
                <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                <p class="errortext" id="passworderror">Your password must contain between 4 and 8 characters.</p>

                <!-- 2e wachtwoord veld + error -->
                <input class="formfield" type="password" name="repeatpassword" id="repeatpassword" placeholder="Repeat password...">
                <p class="errortext" id="passwordrepeaterror">Passwords are not the same.</p>

                <!-- Submit knop -->
                <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                <!-- Remember me + help knop -->
                <div class="content1">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="./loginhelp.php">Need help?</a>
                </div>
            </form>
            <!-- Log in link + verify link -->
            <div class="content2">
                <p>Already have an account? <a href="./login.php">Sign in now</a>.</p>
                <p>Want a verified account? <a href="./verifiedrequest.php">Verify now</a>.</p>
            </div>
        </div>
    </div>
    <?php
    // Form input valideren
    if (isset($_POST['login'])) {
        if (!empty($_POST['email'])) {
            if (!empty($_POST['password'])) {
                if (!empty($_POST['repeatpassword'])) {
                    if (isset($_POST['remember'])) {
                        echo "remember";
                    } else {
                        header('Location: ./choosemembership.php');
                    }
                } else {
                    // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    // en het email veld weer invult
                    echo
                    "<script>
                        document.getElementById('passwordrepeaterror').style.display = 'block';
                        document.getElementById('email').value = '" . $_POST['email'] . "';
                    </script>";
                }
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