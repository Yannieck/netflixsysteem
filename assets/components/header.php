<header>
    <div class="leftHeader">
        <a href="./main.php"><img src="../assets/img/lightlogo.svg" height="120" width="120" alt="logo"></a>
        <ul class="navtekst">
            <li><a href="./askquestion.php">Ask a question</a></li>
            <li><a href="./questions.php">Questions</a></li>
            <li><a href="#">Videos</a></li>
        </ul>
    </div>
    <div class="rightHeader">
        <ul class="navicons">
            <li><input id="zoekBalk" type="text"></li>
            <li><i onclick="showInput()" class="fas fa-search fa-2x"></i></li>
            <li><a href="#"><i class="fas fa-bell fa-2x"></i></a></li>
            <li>
                <i onmouseover="showAccountMenu()" onmouseleave="hideAccountMenu()" class="fas fa-user fa-2x">
                    <div id="accountMenu" class="accountHover">
                        <p><a href="./accountinfo.php">Account</a></p>
                    </div>
                </i>
            </li>
        </ul>
    </div>
</header>
<script>
    const searchIcon = document.getElementById("zoekBalk");
    const accountMenu = document.getElementById("accountMenu");
    searchIcon.style.display = "none";
    accountMenu.style.display = "none";

    function showInput() {
        if (searchIcon.style.display == "inline") {
            searchIcon.style.display = "none";
        } else {
            searchIcon.style.display = "inline";
        }
    }

    function showAccountMenu() {
        accountMenu.style.display = "block";
    }

    function hideAccountMenu() {
        accountMenu.style.display = "none";
    }
</script>