<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
</head>
<body>
    <div class="background">
        <img class="logo" src="../assets/img/lightlogo.svg" alt="logo">
        <div class="holder">
            <div class="content">
                <h1>Sign in</h1>
                <form action="../index.php" method="post">
                    <input class="formfield" type="email" name="email" id="email" placeholder="Email...">
                    <input class="formfield" type="password" name="password" id="password" placeholder="Password...">
                    <input class="button" type="submit" name="login" id="login" value="Sign In">
                    <input type="checkbox">
                </form>
            </div>
        </div>
    </div>
</body>
</html>