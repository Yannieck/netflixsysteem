<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/login/login.css">
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/aside/aside.css">
</head>

<body>
    <?php include_once("../assets/components/header.php"); ?>
    <div class="flex">
        <?php include_once("../assets/components/aside.php"); ?>
        <div class="pageContent">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
                <input type="text" name="title" id="title">
            </form>
        </div>
    </div>
</body>

</html>