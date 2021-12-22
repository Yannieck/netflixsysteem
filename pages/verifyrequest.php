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
        <div class="content width-2">
            <h1>Verified account</h1>
            <p>This is where professional programmers can submit their information to possibly get a professional account.
                This account will be free of charge and you will have a checkmark next to your name.</p>

            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) ?>" method="post">
                <div class="flex">
                    <div class="half-form">

                        <!-- Voledige naam -->
                        <input class="formfield" type="text" name="fullname" id="fullname" placeholder="Full name...">
                        <p class="errortext" id="nameerror">Please enter a valid name.</p>

                        <!-- Email veld -->
                        <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                        <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>

                        <!-- Submit -->
                        <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                        <!-- Remember me + help -->
                        <div class="content1">
                            <a href="./loginhelp.php">Need help?</a>
                        </div>
                    </div>
                    <div class="half-form">
                        <img class="uploadedFoto" src="../assets/img/image_placeholder.png" alt="Your uploaded picture">
                        <input type="file">
                    </div>
                </div>
            </form>
            <!-- Sign up link -->
            <div class="content2">
                <p>New to Null Pointer Videos? <a href="./choosemembership.php">Sign up now</a>.</p>
                <p>Already have an account? <a href="./login.php">Sign in now</a>.</p>
            </div>
        </div>
    </div>
    <?php
    // Form input valideren
    if (isset($_POST['login'])) {
        if (!empty($_POST['fullname'])) {
            $fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_SPECIAL_CHARS);
            if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);


                // Database connectie
                // $query = "SELECT `Password` FROM `account` WHERE Email = ?";

                // $stmt = mysqli_prepare($conn, $query);
                // mysqli_stmt_bind_param($stmt, 's', $email);

                // mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

                // mysqli_stmt_bind_result($stmt, $passResult) or die(mysqli_error($conn));
                // mysqli_stmt_store_result($stmt);

                // mysqli_stmt_fetch($stmt);

                // if (password_verify($_POST['password'], $passResult)) {
                //     header("Location: ./loginredirect.php?rem=" . $remember);
                // } else {
                //     echo "ERROR: Password does not match emailadress.";
                // }
                // mysqli_stmt_close($stmt);
            } else {
                // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                // en het email veld weer invult
                echo
                "<script>
                    document.getElementById('emailerror').style.display = 'block';
                </script>";
            }
        } else {
            // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
            echo
            "<script>
                document.getElementById('nameerror').style.display = 'block';
                document.getElementById('email').value = '" . $_POST['email'] . "';
            </script>";
        }
    }
    ?>

</body>

</html>
<?php include_once("../utils/dbclose.php"); ?>