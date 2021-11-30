<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>

<body>
    <div class="background">
        <a href="./landingpage.php"><img class="logo" src="../assets/img/lightlogo.svg" alt="logo"></a>
        <div class="content">
            <h1>Sign in</h1>
            <form action="../index.php" method="post">
                <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                <input class="formfield" type="password" name="repeatpassword" id="repeatpassword" placeholder="Repeat password...">
                <input class="button" type="submit" name="login" id="login" value="Sign In">

                <div class="content1">
                    <div class="checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#">Need help?</a>
                </div>
                <div class="content2">
                    <p>Already have an account? <a href="./login.php">Sign in now</a>.</p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>