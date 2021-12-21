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
                <div class="content width-1">
                    <h1>Sign up</h1>
                    <p>Selected account type: <?php echo htmlentities($_GET['type'] == 0) ? "Junior" : "Senior"; ?></p>
                    <p>This will cost: &euro;<?php echo htmlentities($_GET['type'] == 0) ? "9.99" : "14.99"; ?></p>
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']) . '?type=' . htmlentities($_GET['type']) ?>" method="post" autocomplete="off">
                        <!-- Username + error -->
                        <input class="formfield" type="text" name="username" id="username" placeholder="Username...">
                        <p class="errortext" id="usernameerror">Please enter a valid username.</p>
                        <p class="errortext" id="usernamelength">A username must be less than 80 characters.</p>

                        <!-- Email veld + error -->
                        <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                        <p class="errortext" id="emailerror">Please enter a valid emailadress.</p>

                        <!-- 1e wachtwoord veld + error -->
                        <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                        <p class="errortext" id="passworderror">Your password must contain more than 4 characters.</p>

                        <!-- 2e wachtwoord veld + error -->
                        <input class="formfield" type="password" name="repeatpassword" id="repeatpassword" placeholder="Repeat password...">
                        <p class="errortext" id="passwordrepeaterror">Passwords are not the same.</p>

                        <!-- Submit knop -->
                        <input class="formbutton" type="submit" name="signup" id="signup" value="Sign Up">

                        <!-- Remember me + help knop -->
                        <div class="content1">
                            <a href="./loginhelp.php">Need help?</a>
                        </div>
                    </form>
                    <!-- Log in link + verify link -->
                    <div class="content2">
                        <p>Already have an account? <a href="./login.php">Sign in now</a>.</p>
                        <p>Want a verified account? <a href="./verifyrequest.php">Verify now</a>.</p>
                    </div>
                </div>
            </div>
            <?php
            // Form input valideren
            if (isset($_POST['signup'])) {
                if (!empty($_POST['username'])) {
                    if (strlen($_POST['username']) < 80) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
                        if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL)) {
                            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                            if ($password = $_POST['password'] && strlen($_POST['password']) >= 4 && strlen($_POST['password']) < 255) {
                                if ($repeatPassword = $_POST['repeatpassword'] && $password == $_POST['repeatpassword']) {
                                    $rawname = explode('@', $email)[0];

                                    $typename = htmlentities($_GET['type']) == 0 ? "Junior" : "Senior";
                                    $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

                                    // Database connectie
                                    $query = "INSERT INTO account (`MembershipName`, `Username`, `Email`, `Password`) VALUES(
                                        ?, ?, ?, ?
                                    )";

                                    $stmt = mysqli_prepare($conn, $query) or die(mysqli_error($conn));
                                    mysqli_stmt_bind_param($stmt, 'ssss', $typename, $username, $email, $hashedPassword);

                                    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));
                                    mysqli_stmt_close($stmt);

                                    header("Location: ./payment.php?type={$_GET['type']}");
                                } else {
                                    // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                                    // en de email en username velden invullen
                                    echo
                                    "<script>
                                        document.getElementById('passwordrepeaterror').style.display = 'block';
                                        document.getElementById('username').value = '" . $_POST['username'] . "';
                                        document.getElementById('email').value = '" . $_POST['email'] . "';
                                    </script>";
                                }
                            } else {
                                // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                                // en de email en username velden invullen
                                echo
                                "<script>
                                    document.getElementById('passworderror').style.display = 'block';
                                    document.getElementById('username').value = '" . $_POST['username'] . "';
                                    document.getElementById('email').value = '" . $_POST['email'] . "';
                                </script>";
                            }
                        } else {
                            // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                            // en de email en username velden invullen
                            echo
                            "<script>
                                document.getElementById('emailerror').style.display = 'block';
                                document.getElementById('username').value = '" . $_POST['username'] . "';
                                document.getElementById('email').value = '" . $_POST['email'] . "';
                            </script>";
                        }
                    } else {
                        // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                        // en de email en username velden invullen
                        echo
                        "<script>
                            document.getElementById('usernamelength').style.display = 'block';
                            document.getElementById('username').value = '" . $_POST['username'] . "';
                            document.getElementById('email').value = '" . $_POST['email'] . "';
                        </script>";
                    }
                } else {
                    // Stukje javascript dat de display van de error van 'none' naar 'block' verandert
                    // en de email en username velden invullen
                    echo
                    "<script>
                        document.getElementById('usernameerror').style.display = 'block';
                        document.getElementById('username').value = '" . $_POST['username'] . "';
                        document.getElementById('email').value = '" . $_POST['email'] . "';
                    </script>";
                }
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