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
                            <input type='url' name='url' id='url' placeholder='Enter a video link here...' required>
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
                                <input type='file' id='videoFileInput' hidden name='video' id='chooseVideo' accept='video/*' required>
                                <input type='button' id='videoFileBtn' value='Upload a Video'>
                                <label id='selectedVideo'>No video selected</label>
                            </div>
                            <div class='file'>
                                <input type='file' id='thumbnailFileInput' hidden name='thumbnail' id='chooseThumbnail' accept='image/*' required>
                                <input type='button' id='thumbnailFileBtn' value='Upload a Thumbnail'>
                                <label id='selectedThumbnail'>No thumbnail selected</label>
                            </div>
                        </div>
                    </div>
                    
                    <input type='submit' name='submit' value='Answer'>
                </div>
            </form>";

        } else {
            echo "  <div class='nocomments'>
                        <p>No comments have been set.</p>
                        <a href='?TitleId=$id&AnswerQuestion' class='btn answer'>Answer</a>
                    </div>";
        }
        // onClick='triggerFileSelector(\"chooseVideo\", \"selectedVideo\");' 
        // onClick='triggerFileSelector(\"chooseThumbnail\", \"selectedThumbnail\");' 
        // ?TitleId=$id
    ?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    // For customizing input[type='file']....

    // On click on the #videoFileBtn -> you can select a file
    $("#videoFileBtn").bind('click', function() {
        $("#videoFileInput").click();
    });

    // On click on the #thumbnailFileBtn -> you can select a file
    $("#thumbnailFileBtn").bind('click', function() {
        $("#thumbnailFileInput").click();
    });

    // On file change -> set innerhtml of #videoFileInput-label
    $("#videoFileInput").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $('#selectedVideo').addClass("selected").text(fileName);
    });
    // On file change -> set innerhtml of #thumbnailFileInput-label
    $("#thumbnailFileInput").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $('#selectedThumbnail').addClass("selected").text(fileName);
    });
</script>