<?php
require_once '../utils/dbconnect.php';
require_once '../utils/functions.php';

if(str_contains($_SERVER["REQUEST_URI"], "&reload")) {
    if(str_contains($_SERVER["REQUEST_URI"], "Notifications=hidden")) {
        setURL("show");
    } else {
        setURL("hidden");
    }
} else if(!str_contains($_SERVER["REQUEST_URI"], "Notifications")) {
    setURL();
}

$userId = $_SESSION['userId'];
$isAdmin = function () use ($conn, $userId) {
    $sql = "SELECT MembershipName FROM account WHERE Id = ?";

    $results = stmtExecute($conn, $sql, 1, "i", $userId);

    $membershipName = $results["MembershipName"][0];
    if ($membershipName === "Admin") {
        return true;
    } else {
        return false;
    }
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
            <li><a href="./askquestion.php">Ask a question</a></li>
            <li><a href="./main.php">Videos</a></li>
            <li><a href="./questions.php">Questions</a></li>
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
                <?php
                    $results = checkNotifications($conn, $userId); 
                    $notify = (is_array($results) && isset($results["All"]) && count($results["All"]) > 0) ? "new" : "" ;
                    $openBox = (isset($_GET['Notifications']) && $_GET['Notifications'] == "show") ? "show" : "" ;

                    echo "<i onclick='showNotificationMenu(\"$openBox\")' class='fas fa-bell fa-2x $notify'>";
                    echo "<div id='notificationMenu' class='overlayHover notificationHover $openBox'>";
                         
                        // debug($results);

                        // Notification is gekoppeld aan de vraag of bookmark
                        if ($openBox == "show") {
                            if(is_array($results) && isset($results["All"]) && count($results["All"]) > 0) {

                                $sqlAccount = "SELECT Name, Username, Photo
                                            FROM account 
                                            WHERE Id = ?";

                                $sqlComment = "SELECT AccountId, Content, QuestionId, CommentDate FROM comment WHERE Id = ?";
                                $sqlQuestion = "SELECT Title FROM question WHERE Id = ?";

                                for($i = 0; $i < count($results["All"]); $i++) {
                                    $commentId = $results["All"][$i];
                                    $getComment = stmtExecute($conn, $sqlComment, 1, "i", $commentId);

                                    $byID = $getComment['AccountId'][0];
                                    $getAccountName = stmtExecute($conn, $sqlAccount, 1, "i", $byID);

                                    $questionId = $getComment["QuestionId"][0];
                                    $getQuestionTitle = stmtExecute($conn, $sqlQuestion, 1, "i", $questionId);

                                    $title = $getQuestionTitle['Title'][0];
                                    $content = $getComment['Content'][0];
                                    $time = $getComment['CommentDate'][0];
                                    
                                    $name = ($getAccountName["Name"][0] == "Unknown") ? $getAccountName["Username"][0] : $getAccountName["Name"][0];
                                    $path = ($getAccountName["Photo"][0] == NULL) ? "unknown.png"  : $getAccountName["Photo"][0];

                                    $sql = "UPDATE notification 
                                            SET isSeen = 1
                                            WHERE CommentId = ? AND AccountId = ?";
                                    stmtExecute($conn, $sql, 1, "ii", $commentId, $userId);

                                    echo "<div class='notification'>
                                        <div class='profile'>
                                            <div class='profile__picture'>
                                                <img src='../assets/img/profiles/$path' alt='$name'>
                                            </div>
                                            <div class='profile__name'>
                                                <p>$name</p>
                                            </div>
                                        </div>
                                        <div class='content'>
                                            <div class='type'>
                                                <a href='questions.php?TitleId=$questionId'>";
                                                    if (is_array($results["Bookmark"]) && in_array($commentId, $results["Bookmark"])) {
                                                        echo "<h4>$name replied to a bookmarked question.</h4>";
                                                    } else {
                                                        echo "<h4>$name replied to your question.</h4>";
                                                    }
                                                echo "</a>
                                                <p>$title<p>
                                            </div>
                                            <div class='msg'>
                                                <p>$content</p>
                                            </div>
                                        </div>
                                        <div class='time'>
                                            <p>";
                                                $time = calculateDate($time);
                                                $time = str_replace(",", ",<br>",$time);
                                            echo "$time ago</p>
                                        </div>
                                    </div>";
                                }
                                unset($results);
                                
                            } else {
                                echo "<p>No Notifications Available.</p>";
                            }
                        }

                        ?>
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

    function showNotificationMenu(show) {
        if(show != "show") {
            accountMenu.style.display = "none";
            searchIcon.style.display = "none";
            searchbtn.style.display = "none";
            window.location = window.location.href + "&reload";
        } else {
            window.location = window.location.href + "&reload";
        }
    }
</script>