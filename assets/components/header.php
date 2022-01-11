<?php
$isAdmin = function () use ($conn) {
    $userId = $_SESSION['userId'];
    $query = "SELECT `MembershipName` FROM `account` WHERE `account`.`Id` = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userId);

    mysqli_stmt_execute($stmt) or die(mysqli_error($conn));

    mysqli_stmt_bind_result($stmt, $membershipName) or die(mysqli_error($conn));
    mysqli_stmt_store_result($stmt);

    mysqli_stmt_fetch($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        if ($membershipName === "Admin") {
            return true;
        } else {
            return false;
        }
    }
    mysqli_stmt_close($stmt);
};


if (isset($_POST['searchSubmit'])) {
    if (!empty($_POST['searchbar'])) {
        $searchInput = filter_input(INPUT_POST, 'searchbar', FILTER_SANITIZE_SPECIAL_CHARS);
        header("Location: ./main.php?search=" . $searchInput);
    }
}
?>
<header>
    <div class="leftHeader">
        <a href="./main.php"><img src="../assets/img/lightlogo.svg" height="120" width="120" alt="logo"></a>
        <ul class="navtekst">
            <li><a href="./main.php">Videos</a></li>
            <li><a href="./questions.php">Questions</a></li>
            <li><a href="./askquestion.php">Ask a question</a></li>
        </ul>
    </div>
    <div class="rightHeader">
        <ul class="navicons">
            <li>
                <form action="./main.php" method="post">
                    <input class="input" name="searchbar" id="searchbar" type="text" placeholder="Search for something...">
                    <input class="btn" name="searchSubmit" id="searchbtn" type="submit" value="Search">
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
                        <?php if ($isAdmin()) { ?>
                            <a href="./adminpanel.php">Admin</a>
                        <?php } ?>
                        <a href="./profile.php">Account</a>
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
    notificationMenu.style.display = "none";


    var closeMenu = function(element) {
        return function curried_func(event) {
            if (element.style.display == "block") {
                if (element.parentNode !== event.target) {
                    element.style.display = "none";
                }
            }
        }
    }
    document.addEventListener("click", closeMenu(accountMenu));
    document.addEventListener("click", closeMenu(notificationMenu));

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
        if (notificationMenu.style.display == "none") {
            notificationMenu.style.display = "block";

            accountMenu.style.display = "none";
            searchIcon.style.display = "none";
            searchbtn.style.display = "none";
        } else {
            notificationMenu.style.display = "none";
        }
    }
</script>