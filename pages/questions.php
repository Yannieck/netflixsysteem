<?php
include_once("../assets/components/loginCheck.php");
require '../utils/dbconnect.php';
require '../utils/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once '../assets/components/head.php'; ?>
    <link rel="stylesheet" href="../assets/styles/header/header.css">
    <link rel="stylesheet" href="../assets/styles/aside/aside.css">
    <link rel="stylesheet" href="../assets/styles/questions/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/styles/vs2015.min.css">
</head>
<body>
   <?php 
   require_once '../assets/components/header.php'; 
   ?> 
   <div class="container">
       <?php
        require_once '../assets/components/aside.php';

        // If GET["Title"] is set
    	if(isset($_GET["TitleId"]) && $_GET["TitleId"] == filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT)) {
            
            $id = filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT);

            $sql = "SELECT Title, Content, AskDate, AccountId FROM question WHERE Id = ?";
            $info = stmtExecute($conn, $sql, 1, 'i', $id);

            if(isset($_GET['bookmark'])) {
                $sql = "SELECT AccountId FROM bookmark WHERE QuestionId = ?";
                $bookmarks = stmtExecute($conn, $sql, 1, "i", $id);
            
                if(isset($bookmarks) && in_array($_SESSION['userId'], $bookmarks['AccountId'])) {
                    $id = filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT);
                    $accId = $_SESSION['userId'];
                    $sql = "DELETE FROM bookmark WHERE QuestionId = ? AND AccountId = ?";
                    stmtExecute($conn, $sql, 1, "ii", $id, $accId);
                } else {
                    $id = filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT);
                    $accId = $_SESSION['userId'];
                    $sql = "INSERT INTO bookmark (AccountId, QuestionId) VALUES (?, ?)";
                    stmtExecute($conn, $sql, 1, "ii", $accId, $id);
                }
            }
        
            $sql = "SELECT AccountId FROM bookmark WHERE QuestionId = ?";
            $bookmarks = stmtExecute($conn, $sql, 1, "i", $id);
            
            if(isset($bookmarks) && in_array($_SESSION['userId'], $bookmarks['AccountId'])) {
                $mark = 'fas';
            } else {
                $mark = 'far';
            }

            // $questions["Title"] as $index => $title  
            $accountId = $info['AccountId'][0];
            $title = $info['Title'][0];
            $askDate = $info['AskDate'][0];
            $content = $info['Content'][0]; 
            $content = str_replace('%10;', "</p><p>", $content);            // Enter
            $content = str_replace('%11;', "</p><pre><code>", $content);    // Start Code Block
            $content = str_replace('%12;', "</code></pre><p>", $content);   // Einde Code block
            $content = str_replace('%13;', "\t", $content);                 // Tab in de code block
            $content = str_replace('%14;', "\n", $content);                 // Enter in de code block

        
            echo "<div class='specific__question'>
                <div class='question'>
                    <div class='question__head'>
                        <div class='main__info'>
                            <div class='question__title'>
                                <h2>$title</h2>
                            </div>
                            <div class='question__info'>
                                <div class='question__ask-date'>
                                    <p>asked ".calculateDate($askDate)." ago</p>
                                </div>
                                <div class='question__tags'>";

                                    $sql = "SELECT SubCategory FROM subtag WHERE Id IN (SELECT SubTagId FROM tag_question WHERE QuestionId = ?)";

                                    $tags = stmtExecute($conn, $sql, 1, "i", $id);
                                    foreach($tags["SubCategory"] as $index => $TagName) {
                                        echo "<p class='tag'>$TagName</p>";
                                    }

                                echo "</div>
                            </div>
                        </div>
                        <i class='$mark fa-bookmark' id='bookmarkIcon' onclick='bookmark($id);'></i>
                    </div>
                    <div class='question__content'>
                        <div class='profile'>";

                            $sql = "SELECT Username, Name, Photo FROM account WHERE Id = ?";
                            $profileInfo = stmtExecute($conn, $sql, 1, "i", $accountId);

                            $name = ($profileInfo['Username'][0] !== NULL) ? $profileInfo['Username'][0] : $profileInfo['Name'][0];
                            $photo = ($profileInfo['Photo'][0] !== NULL) ? $profileInfo['Photo'][0] : 'unknown.png';

                            echo "<div class='profile__picture'>
                                <img src='../assets/img/profiles/$photo' alt='$name'>
                            </div>
                            <div class='profile__name'>
                                <p>$name</p>
                            </div>";


                        echo "</div>
                        <div class='question__main'><p>$content</p></div>
                    </div>
                </div>";
                
                $sql = "SELECT AccountId, Content, CommentDate, VideoId FROM comment WHERE QuestionId = ?";
                $comments = stmtExecute($conn, $sql, 1, "i", $id);
                if($comments) {

                    $accountId = $comments['AccountId'][0];
                    $content = $comments['Content'][0];
                    $commentDate = $comments['CommentDate'][0];
                    $video = $comments['VideoId'][0];


                    echo "<div class='comments'>
                    <div class='profile'>
                        <div class='profile__picture'>";

                            $sql = "SELECT Username, Name, Photo FROM account WHERE Id = ?";
                            $profileInfo = stmtExecute($conn, $sql, 1, "i", $accountId);

                            $name = ($profileInfo['Username'][0] !== NULL) ? $profileInfo['Username'][0] : $profileInfo['Name'][0];
                            $photo = ($profileInfo['Photo'][0] !== NULL) ? $profileInfo['Photo'][0] : 'unknown.png';

                            echo "<img src='../assets/img/profiles/$photo' alt='$name'>
                            <div class='profile__name'>
                                <p>$name</p>
                            </div>
                        </div>
                        <div class='time'>
                            <p>".calculateDate($commentDate)." ago</p>
                        </div>
                    </div>
                    <div class='comment__content'>
                        <div class='card'>";

                            $sql = "SELECT File, Thumbnail FROM video WHERE Id = ?";
                            $videoInfo = stmtExecute($conn, $sql, 1, "i", $video);

                            $videoFile = '../assets/upload/videos/'.$videoInfo['File'][0];
                            $thumbnail = '../assets/upload/thumbnails/'.$videoInfo['Thumbnail'][0];

                            $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mime = finfo_file($fileinfo, $videoFile);

                            echo "<img id='card-top' src='$thumbnail' alt='$title'>
                            <video id='commentVideo' preload='metadata' loop muted>
                                <source src='$videoFile' type='$mime'>
                            </video>
                        </div>
                        <div class='comment__text'>
                            <p>$content</p>
                        </div>
                    </div>";
                } else {
                    echo "<form action='?TitleId=$id&uploadVideo' enctype='multipart/form-data'>
                        <div class='form__container'>
                            <div class='top'>
                                <div class='left'>
                                    <input type='text' placeholder='Title'>
                                    <textarea name='description' id='description' placeholder='Description'></textarea>
                                </div>
                                <div class='file'>
                                    <input type=file hidden id='choose' accept='video/*'>
                                    <input type='button' onClick='getFile.simulate();' value='Upload a Video'>
                                    <label id='selected'>Nothing video selected</label>
                                </div>
                            </div>
                            
                            <input type='submit' value='Answer'>
                        </div>
                    </form>";
                }
            echo "</div></div>";

        } else {

        // Else
            $sql = "SELECT Id, Title, AskDate FROM question ORDER BY AskDate DESC";
            $questions = stmtExecute($conn, $sql, 2);

            echo "<div class='questions__wrapper'>";

            foreach ($questions["Title"] as $index => $title) {

                $askDate = $questions["AskDate"][$index];
                $id = $questions["Id"][$index];

                echo "
                <div class='question'>
                    <div class='question__title'>
                        <a href='?TitleId=$id'>
                            <h2>$title</h2>
                        </a>
                    </div>
                    <div class='question__info'>
                        <div class='question__tags'>"; 

                $sql = "SELECT SubCategory FROM subtag WHERE Id IN (SELECT SubTagId FROM tag_question WHERE QuestionId = ?)";

                $tags = stmtExecute($conn, $sql, 1, "i", $id);
                foreach($tags["SubCategory"] as $index => $TagName) {
                    echo "<p class='tag'>$TagName</p>";
                }
                echo "</div>
                        <div class='question__ask-date'>
                            <p>asked ".calculateDate($askDate)." ago</p>
                        </div>
                    </div>
                </div>
                ";
            }
            echo "</div>";
        }

       ?>
   </div>
   <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/11.3.1/highlight.min.js"></script>
   <script>hljs.highlightAll();</script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
        // jQuery for: document.getElementsByClassName('.card') and listen to onHover and play hoverVideo, else play hideVideo.
        $('.card').hover(hoverVideo, hideVideo);
        var video = document.getElementById('commentVideo');
        if(video) {
            video.disablePictureInPicture = true;
        }
        var img = document.getElementById('card-top');
        var videoEnd;
        var checkVideo;

        // Show or hide the element in CSS
        function hide(element) {
            element.style.display = 'none';
        }
        function show(element) {
            element.style.display = 'block';
        }

        // Set interval during the play time of the video
        function checkVideoTime() {
            if(video.currentTime >= videoEnd) {
                hideVideo();
            }
        }

        function checkVideoDuration() {
            // Video must be 100 seconds or longer. This so you don't see alot of the video.
            if (video.duration >= 100) {
                // Randomly start the video at 5% of the video
                video.currentTime = video.duration / 20;
                videoEnd = video.currentTime + 5;
                if(!(video.currentTime <= videoEnd)) {
                    console.log("The length of the video is too short to play.");
                    return 0;
                } else {
                    return 1;
                }
            } else {
                console.log("The length of the video is too short to play.");
                return 0;
            }
        };

        function hoverVideo(e) {  
            // Check the duration of the video
            if(checkVideoDuration()) {
                // Show the video
                show(video);

                // Play the video and catch problems
                video.play().catch(
                    function(e) {
                        console.log(e.message);
                    }
                ); 

                // Hide the image
                hide(img);  

                // Check the video current time during the play time of the video
                checkVideo = setInterval(checkVideoTime, 1000);
            }
        }


        // Hide the video
        function hideVideo(e) {
            // Stop the video with playing
            video.pause();

            // Hide the video
            hide(video);

            // Show the image
            show(img);

            // Clear interval
            clearInterval(checkVideo);
        }
   </script>
   <script>
        const bookmarkIcon = $("#bookmarkIcon");

        function bookmark(id) {
            bookmarkIcon.toggleClass("far");
            bookmarkIcon.toggleClass("fas");
            window.location = 'questions.php?TitleId=' + id + '&bookmark';
        }
   </script>
   <script src="https://rawgit.com/thielicious/selectFile.js/master/selectFile.js"></script>
   <script>
        var getFile = new selectFile;
        getFile.targets('choose','selected');
   </script>
</body>
</html>
