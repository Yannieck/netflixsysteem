<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <header>
        <div class="leftHeader">
            <a href="index.php"><img src="../assets/img/lightlogo.svg" height="120" width="120" alt="logo"></a>
            <ul class="navtekst">
                <li><a href="">Ask a question</a></li>
                <li><a href="">Questions</a></li>
                <li><a href="">Videos</a></li>
            </ul>
        </div>
        <div class="rightHeader">
            <ul class="navicons">
                <li><input id="zoekBalk" type="text"></li>
                <li><i onclick="showInput()" class="fas fa-search fa-2x"></i></li>
                <li><a href=""><i class="fas fa-bell fa-2x"></i></a></li>
                <li><a href=""><i class="fas fa-user fa-2x"></i></a></li>
            </ul>
        </div>
    </header>
    <script>
        const zoekbalkIcoon = document.getElementById("zoekBalk");
        function showInput(){
            if(zoekbalkIcoon.style.display == "inline"){
                zoekbalkIcoon.style.display = "none";
            }else{
                zoekbalkIcoon.style.display = "inline";
            }
        }
    </script>
</body>
</html>