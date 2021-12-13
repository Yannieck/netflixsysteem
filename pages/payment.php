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
            <h1>Pay here</h1>
            <p>Je betaalt: &euro;<?php echo htmlspecialchars($_GET['type'] == 0 ? "9,99" : "14,99") ?></p>
            <a href="./accountCreated.php" class="button">Pay</a>
        </div>
    </div>
</body>

</html>