<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once('../assets/components/head.php') ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/aside/aside.css">
    <link rel="stylesheet" href="../assets/styles/mainpage/main.css">
</head>

<body>
    <?php
    include_once("../assets/components/header.php") ; 
    ?>
    <div class="flex">
        <?php  include_once("../assets/components/aside.php"); ?>
        <div class="main-container">
            <div class="filter">
                <div class="flex-container">
                    <div class="logos">
                        <img src="../assets/icons/HTML.svg" alt="HTML">
                        <p>&lt;HTML&sol;&gt;</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/PHP.svg" alt="php">
                        <p>&lt;?php</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/JavaScript.svg" alt="JavaScript">
                        <p>Javascript;</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/CSharp.svg" alt="C#">
                        <p>C#</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/CSS.svg" alt="CSS">
                        <p>CSS</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/Java.svg" alt="Java">
                        <p>Java</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/Python.svg" alt="Python">
                        <p>Python</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/Android.svg" alt="Android">
                        <p>Android</p>
                    </div>
                    <div class="logos">
                        <img src="../assets/icons/Apple.svg" alt="Apple">
                        <p>Apple</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>