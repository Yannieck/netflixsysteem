<?php include_once("../utils/dbconnect.php"); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/login/login.css">
</head>

<?php
if (isset($_GET['type'])) {
    if ($_GET['type'] == 0 || $_GET['type'] == 1) {
?>

        <body>
            <div class="background center">
                <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
                <div class="content">
                    <h1>Sign up</h1>
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?type=' . htmlentities($_GET['type']) ?>" method="post">
                        <!-- Email veld + error -->
                        <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                        <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>

                        <!-- 1e wachtwoord veld + error -->
                        <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                        <p class="errortext" id="passworderror">Your password must contain more than 8 characters.</p>

                        <!-- 2e wachtwoord veld + error -->
                        <input class="formfield" type="password" name="repeatpassword" id="repeatpassword" placeholder="Repeat password...">
                        <p class="errortext" id="passwordrepeaterror">Passwords are not the same.</p>

                        <!-- Submit knop -->
                        <input class="formbutton" type="submit" name="login" id="login" value="Sign In">

                        <!-- Remember me + help knop -->
                        <div class="content1">
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
                if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                    if ($password = $_POST['password'] && strlen($_POST['password']) >= 8) {
                        if ($repeatPassword = $_POST['repeatpassword'] && $password == $_POST['repeatpassword']) {
                            if (isset($_POST['remember'])) {
                                echo "remember";
                            } else {
                                $rawname = explode('@', $email)[0];

                                $typename = htmlentities($_GET['type']) == 0 ? "Junior" : "Senior";
                                $name = htmlspecialchars($rawname);
                                $username = htmlspecialchars($rawname);
                                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

                                // Database connectie
                                $query = "INSERT INTO account (`MembershipName`, `Name`, `Username`, `Email`, `Password`) VALUES(
                                    ?, ?, ?, ?, ?
                                )";

                                $stmt = mysqli_prepare($conn, $query) or die(mysqli_error($conn));
                                mysqli_stmt_bind_param($stmt, 'sssss', $typename, $name, $name, $email, $hashedPassword);

                                mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                                mysqli_stmt_close($stmt);

                                header("Location: ./payment.php?type={$_GET['type']}");
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
                    // } else {
                    //     // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    //     echo
                    //     "<script>
                    //     document.getElementById('emailerror').style.display = 'block';
                    // </script>";
                    // }
                    // } else {
                    //     // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    //     echo
                    //     "<script>
                    //     document.getElementById('emailerror').style.display = 'block';
                    // </script>";
                    // }
                } else {
                    // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    echo
                    "<script>
                        document.getElementById('emailerror').style.display = 'block';
                    </script>";
                }
            }

            function createAccount()
            {
            }
            ?>
        </body>
<?php
    } else {
        echo "ERROR: invalid account type";
    }
} else {
    echo "ERROR: 404, page not found";
}
?>

</html>
<?php include_once("../utils/dbclose.php"); ?>