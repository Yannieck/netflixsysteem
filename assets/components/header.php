<header>
    <div class="leftHeader">
        <a href="./main.php"><img src="../assets/img/lightlogo.svg" height="120" width="120" alt="logo"></a>
        <ul class="navtekst">
            <li><a href="./askquestion.php">Ask a question</a></li>
            <li><a href="#">Questions</a></li>
            <li><a href="./main.php">Videos</a></li>
        </ul>
    </div>
    <div class="rightHeader">
        <ul class="navicons">
            <li>
                <form action="./main.php" method="post">
                    <input class="input" id="searchbar" type="text">
                    <input class="btn" id="searchbtn" type="submit" value="Search">
                </form>
            </li>
            <li><i onclick="showInput()" class="fas fa-search fa-2x"></i></li>
            <li>
                <i onclick="showNotificationMenu()" class="fas fa-bell fa-2x">
                    <div id="notificationMenu" class="overlayHover notificationHover">
                        <a href="#">Test</a>
                    </div>
                </i>
            </li>
            <li>
                <i onclick="showAccountMenu()" class="fas fa-user fa-2x">
                    <div id="accountMenu" class="overlayHover accountHover">
                        <a href="./accountinfo.php">Account</a>
                        <a href="./logout.php">Log out</a>
                    </div>
                </i>
            </li>
        </ul>
    </div>
</header>
<script>
    const searchIcon = document.getElementById("searchbar");
    const searchbtn = document.getElementById("searchbtn");
    const accountMenu = document.getElementById("accountMenu");
    const notificationMenu = document.getElementById("notificationMenu");
    
    searchIcon.style.display = "none";
    searchbtn.style.display = "none";
    accountMenu.style.display = "none";
    notificationMenu.style.display = "block";

    function showInput() {
        if (searchIcon.style.display == "none") {
            searchIcon.style.display = "inline";
            searchbtn.style.display = "inline";

            accountMenu.style.display = "none";
            notificationMenu.style.display = "none";
        } else {
            searchIcon.style.display = "none";
            searchbtn.style.display = "none";
        }
    }

    function showAccountMenu() {
        if (accountMenu.style.display == "none") {
            accountMenu.style.display = "block";

            searchIcon.style.display = "none";
            searchbtn.style.display = "none";
            notificationMenu.style.display = "none";
        } else {
            accountMenu.style.display = "none";
        }
    }

    function showNotificationMenu() {
        if(notificationMenu.style.display == "none") {
            notificationMenu.style.display = "block";
            
            accountMenu.style.display = "none";
            searchIcon.style.display = "none";
            searchbtn.style.display = "none";
        } else {
            notificationMenu.style.display = "none";
        }
    }
</script>