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

    header("Location: ./landingpage.php");
    ?>
    </p>
</body>

</html>