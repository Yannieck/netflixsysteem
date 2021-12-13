<header>
    <div class="leftHeader">
        <a href="../index.php"><img src="../assets/img/lightlogo.svg" height="120" width="120" alt="logo"></a>
        <ul class="navtekst">
            <li><a class="headerBtn" href="./askquestion.php">Ask a question</a></li>
            <li><a class="headerBtn" href="./questions.php">Questions</a></li>
            <li><a class="headerBtn" href="#">Videos</a></li>
        </ul>
    </div>
    <div class="rightHeader">
        <ul class="navicons">
            <li><input id="zoekBalk" type="text"></li>
            <li><i onclick="showInput()" class="fas fa-search fa-2x"></i></li>
            <li><a href="#"><i class="fas fa-bell fa-2x"></i></a></li>
            <li><a href="#"><i class="fas fa-user fa-2x"></i></a></li>
        </ul>
    </div>
</header>
<script>
    const zoekbalkIcoon = document.getElementById("zoekBalk");

    function showInput() {
        if (zoekbalkIcoon.style.display == "inline") {
            zoekbalkIcoon.style.display = "none";
        } else {
            zoekbalkIcoon.style.display = "inline";
        }
    }
</script>