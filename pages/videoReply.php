<?php
/*
 * Onder elke video moet gereageerd kunnen worden met een video
 * Voorwaardens:
 *      - Het bevat 1 video:
 *              * het is een link van een bestaande video
 *              * het is een video bestand
 *                      - Redirect naar uploaden van een video
 *      - De video is niet van jezelf
*/


// Include Styles:
?>
<link rel="stylesheet" href="../assets/styles/questions/videoReply.css">
<div class="videoReply">
<?php

// Require nessecary files:
require_once "../utils/functions.php";

if(isset($_GET["AnswerQuestion"])) {
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    echo "<div class='answerQuestion'>
            <form action='answerQuestion.php?TitleId=$id' class='URLForm' enctype='multipart/form-data' method='POST'>
                <div class='form-seperate url'>
                    <p>Give a video URL</p>
                    <input type='url' name='url' id='url' placeholder='$url' required>
                    <textarea name='description' placeholder='Comment Here...'></textarea>
                </div>
                <input type='submit' name='submit' class='btn form' value='Reply'>
            </form>
            <div class='video-select-option'>
                <p>Select a video</p>
                <a href='?TitleId=$id&UploadVideo' class='btn upload'>Upload Video</a>
            </div>
        </div>";
} else if(isset($_GET["UploadVideo"])) {
    echo "<form action='?TitleId=$id' class='uploadForm' enctype='multipart/form-data' method='POST'>
        <div class='form__container'>
            <div class='top'>
                <div class='left'>
                    <input type='text' name='title' placeholder='Title' required>
                    <textarea name='description' id='description' placeholder='Description' required></textarea>
                </div>
                <div class='right'>
                    <div class='file'>
                        <input type='file' hidden name='video' id='chooseVideo' accept='video/*' required>
                        <input type='button' onClick='triggerFileSelector(\"chooseVideo\", \"selectedVideo\");' value='Upload a Video'>
                        <label id='selectedVideo'>No video selected</label>
                    </div>
                    <div class='file'>
                        <input type='file' hidden name='thumbnail' id='chooseThumbnail' accept='image/*' required>
                        <input type='button' onClick='triggerFileSelector(\"chooseThumbnail\", \"selectedThumbnail\");' value='Upload a Thumbnail'>
                        <label id='selectedThumbnail'>No thumbnail selected</label>
                    </div>
                </div>
            </div>
            
            <input type='submit' value='Answer'>
        </div>
    </form>";

} else {
    echo "  <div class='nocomments'>
                <p>No comments have been set.</p>
                <a href='?TitleId=$id&AnswerQuestion' class='btn answer'>Answer</a>
            </div>";
}

// <div class='form-seperate'>
//                     <label>Select a video</label>
//                     <a href='?TitleId=$id&Upload Video' class='btn upload'>Upload Video</a>
//                     <div class='file'>
//                         <input type='file' hidden name='video' class='file-input' id='chooseVideo' accept='video/*' required>
//                         <span class='file-btn'>Browse</span>
//                         <label class='file-input-label' for='chooseVideo' id='selectedVideo'> No video selected</label>
//                     </div>
//                 </div>

?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    // For customizing input[type='file']....

    // On click on the .file-btn -> you can select a file
    $(".file-btn").bind('click', function() {
        $(".file-input").click();
    });

    // On file change -> set innerhtml of .file-input-label
    $(".file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".file-input-label").addClass("selected").html(fileName);
    });
</script>