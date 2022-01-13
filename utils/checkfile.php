<?php

require_once '../utils/functions.php';

define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

function uploadFile(Array $video, Array $thumbnail) : bool {
    if($video["size"] < 20*GB) {
        $acceptedVideoTypes = ["video/x-flv", 
                                "video/mp4", 
                                "application/x-mpegURL", 
                                "video/MP2T", 
                                "video/3gpp", 
                                "video/quicktime", "
                                video/x-msvideo", 
                                "video/x-ms-wmv"];

        $acceptedImageTypes = ["image/avif", 
                                "image/jpeg", 
                                "image/png"];

        $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
        $uploadedVideoType = finfo_file($fileinfo, $video["tmp_name"]);
        $uploadedThumbnailType = finfo_file($fileinfo, $thumbnail["tmp_name"]);

        if(in_array($uploadedVideoType, $acceptedVideoTypes)) {
            if(in_array($uploadedThumbnailType, $acceptedImageTypes)) {
                $title = filter_input(INPUT_POST, "title", FILTER_SANITIZE_STRING);
                if(file_exists("../assets/upload/videos/" . $title)){
                    echo "This video already exists. ";
                    return false;
                } else if (file_exists("../assets/upload/thumbnails/".$thumbnail["name"])) { 
                    echo "This thumbnail already exists.";
                    return false;
                 } else {
                    if(move_uploaded_file($video["tmp_name"], "../assets/upload/videos/". $title) && move_uploaded_file($thumbnail["tmp_name"], "../assets/upload/thumbnails/". $thumbnail["name"])){
                        $sql = "INSERT INTO video (QuestionId, AccountId, File, Thumbnail) VALUES (?, ?, ?, ?)";
                        $sql2 = "INSERT INTO comment (VideoId, AccountId, Content, QuestionId) VALUES (?, ?, ?, ?)";
                        $sql3 = "SELECT Id FROM video WHERE QuestionId = ?";

                        $questionId = filter_input(INPUT_GET, "TitleId", FILTER_VALIDATE_INT);
                        $accId = $_SESSION['userId'];
                        $description = filter_input(INPUT_POST, "description", FILTER_SANITIZE_STRING);
                        $thumbnailPath = $thumbnail["name"];

                        if(stmtExecute($sql, 1, "iiss", $questionId, $accId, $title, $thumbnailPath)) {
                            $videoIdArray = stmtExecute($sql3, 1, "i", $questionId);
                            $videoId = $videoIdArray["Id"][0];
                            if(stmtExecute($sql2, 1, "iisi", $videoId ,$accId, $description, $questionId)) {
                                return true;
                            } else {
                                $sql = "DELETE FROM video WHERE Id = ?";
                                stmtExecute($sql, 1, "i", $videoId);
                                return false;
                            }
                        } else {
                            unlink("../assets/upload/videos/". $title);
                            unlink("../assets/upload/thumbnails/". $thumbnailPath);
                            return false;
                        }
                    } else {
                        echo "Something went wrong while uploading.";
                        return false;
                    }                    
                }
            } else {
                echo "Uploaded image is not a valid image type!";
                return false;
            }
        } else {
            echo "The uploaded file is not an accepted video file type!";
            return false;
        }
    } else {
        echo "The video file is too large!";
        return false;
    }
}
?>