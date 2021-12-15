<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>
    <?php
    session_start();
    session_destroy();

    unset($_COOKIE['rememberLoggedIn']);
    setcookie('rememberLoggedIn', null, -1);

    echo "You have been logged out.<br>";
    echo "You can log back in with the link underneath.<br><br>";

    echo "<a href='./login.php'>Log in</a>";
    ?>
    </p>
</body>

</html>