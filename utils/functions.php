<?php

// Calculate Time Difference
function calculateDate($timestamp) : string {
    $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($timestamp);

    $years = floor($diff / (365*60*60*24));
    $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
    $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
    $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
    $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60) / (60));
    $seconds = floor($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60);
    
    if ($years == 0) {
        if ($months == 0) {
            if ($days == 0) {
                if ($hours == 0) {
                    if ($minutes == 0) {
                        return "$seconds seconds";
                    } else {
                        return "$minutes min";
                    }
                } else {
                    return "$hours hrs, $minutes min";
                }
            } else {
                return "$days days, $hours hrs";
            }
        } else {
            return "$months months, $days days";
        }
    } else {
        return "$years yrs, $months months";
    }
}

function fail($code = NULL, $info = NULL) {
    switch ($code) {
        // Database Fail: Common
            case 'DB00':
                echo "Statement Preparation Error! $info";
                break;
            case 'DB01':
                echo "Statement Execution Error! $info";
                break;
            case 'DB02':
                echo "Cannot bind the result to the variables. $info";
                break;
        // Database Fail: With Binding
            case 'DB10':
                echo "No information variables are given while this is needed!";
                break;
            case 'DB11':
                echo "You have to give more information variables for this statement! You need to have $info variables.";
                break;
            case 'DB12':
                echo "You have to set chars for each bind parameter to execute this statement! \nChoose between 's' (string), 'i' (integer), 'd' (double) or 'b' (blob).";
                break;
            case 'DB13':
                echo "You have to give more / less chars for this statement! You need to have $info chars.";
                break;
            case 'DB14':
                echo "Cannot bind the parameters for this statement. $info";
                break;
            case 'DB15':
                echo "You have given invalid chars as bind chars! You only can choose between: 's' (string), 'i' (integer), 'd' (double) or 'b' (blob).";
                break;
        default:
            echo "Something went wrong";
            break;
    }
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////
//                                                                                                      //
// @Param $connection: Use $conn from dbconnect.php                                                     //
// @Param $code: Use a code for fail messages, You can easily create 1 above                            //
// @Param $Paramchars: Use this when need to use WHERE conditions -> Use given type: s, i, d or b       //
// @Param $BindParamVars: Use this when need to use WHERE conditions -> Use known DB variables          //
// @Param $sql: Give the sql query to execute                                                           //
//                                                                                                      //
// By:          Joris Hummel                                                                            //
//                                                                                                      //
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function stmtExecute($connection, string $sql, int $code, string $ParamChars = NULL, ...$BindParamVars) : array| bool {

    // Check if the statement can be prepared
    if($stmt = mysqli_prepare($connection, $sql)) {

        // If true
        // Check if the statement needs to bind
        if(substr_count($sql, "?")) {
            
            // If true
            // Check if the bind param chars are set
            if(!empty($ParamChars)) {

                // Check if the given chars are valid
                for ($i=0; $i<strlen($ParamChars); $i++) {
                    switch($ParamChars[$i]) {
                        case 's':
                        case 'i':
                        case 'd':
                        case 'b':
                            // Valid, set $continue to true
                            // Break the inner loop and continue the loop
                            $continue = 1;
                            break 1;
                        default:
                            // Not valid, set $continue to false
                            // Break the outer loop
                            $continue = 0;
                            break 2;
                    }
                }
                
                if($continue) {
                    // If true
                    // Check if the length of the chars is the same as the total bind requests in the statement
                    if(strlen($ParamChars) == substr_count($sql, "?")) {

                        // If true
                        // Check if there are variable names given in the parameters
                        if(!empty($BindParamVars)) {

                            // If true
                            // Check if the amount of variables is the same as the bind request in the statement
                            if(count($BindParamVars) == substr_count($sql, "?")) {
                                
                                // If true
                                // Check if it's possible to bind and continue the function
                                if(!mysqli_stmt_bind_param($stmt, $ParamChars, ...$BindParamVars)) {
                                    fail("DB".$code."4", mysqli_error($connection));
                                    return false;
                                } 
                            } else {
                                fail("DB".$code."1", substr_count($sql, "?"));
                                return false;
                            }
                        } else {
                            fail("DB".$code."0");
                            return false;
                        }
                    } else {
                        fail("DB".$code."3", substr_count($sql, "?"));
                        return false;
                    }
                } else {
                    fail("DB".$code."5");
                    return false;
                }
            } else {
                fail("DB".$code."2");
                return false;
            }
        }  

        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0) {

                $sql = str_replace("DISTINCT ", "", $sql);
                $totalFROMKey = substr_count($sql, "FROM");
                $totalENDKey = substr_count($sql, ")");
                $totalOPENKey = substr_count($sql, "(");
                
                // Check FROM
                for($i = 0; $i < $totalFROMKey; $i++) {
                    if($i === 0) {
                        $posFROMKey[$i] = strpos($sql, "FROM");
                    } else {
                        $posFROMKey[$i] = strpos($sql, "FROM", $posFROMKey[$i - 1] + 1);
                        if($i - 1 >= 0 && $posFROMKey[$i] == $posFROMKey[$i - 1]) {
                            $posFROMKey[$i] = strpos($sql, "FROM", $posFROMKey[$i - 1] + 1);
                        }
                    }
                }

                // Check nested query open sign
                for($i = 0; $i < $totalOPENKey; $i++) {
                    if($i === 0) {
                        $posOPENKey[$i] = strpos($sql, "(");
                    } else {
                        $posOPENKey[$i] = strpos($sql, "(", $posOPENKey[$i - 1] + 1);
                        if($i - 1 >= 0 && $posOPENKey[$i] == $posOPENKey[$i - 1]) {
                            $posOPENKey[$i] = strpos($sql, "(", $posOPENKey[$i - 1] + 1);
                        }
                    }
                }

                // Check nested query end sign
                for($i = 0; $i < $totalENDKey; $i++) {
                    if($i === 0) {
                        $posENDKey[$i] = strpos($sql, ")");
                    } else {
                        $posENDKey[$i] = strpos($sql, ")", $posENDKey[$i - 1] + 1);
                        if($i - 1 >= 0 && $posENDKey[$i] == $posENDKey[$i - 1]) {
                            $posENDKey[$i] = strpos($sql, ")", $posENDKey[$i - 1] + 1);
                        }
                    }
                }

                // debug($posOPENKey);
                // debug($posENDKey);
                // debug($posFROMKey);
                
                // Get Right positions in nested queries and form for array values
                for($k = 0; $k < count($posFROMKey); $k++) {
                    $posFrom = $posFROMKey[$k];
                    if(!empty($posENDKey) && !empty($posOPENKey)) {

                        if($posOPENKey[0] > $posFROMKey[0]) {
                            goto finish;
                        }
                       
                        for($i = 0; $i < count($posOPENKey); $i++) {
                            $posOpen = $posOPENKey[$i];
                            $posEnd = $posENDKey[$i];
                            // echo "$i, $posOpen, $posEnd, $posFrom<br>";
                            if($posFrom > $posEnd && $posEnd > $posOpen) {
                                if($i + 1 < $totalOPENKey && $posOPENKey[$i + 1] > $posFrom && $posENDKey[$i + 1] > $posOPENKey[$i + 1]) {
                                    goto finish;
                                } else if($i + 1 == $totalOPENKey) {
                                    goto finish;
                                }
                            }
                        }
                    } 
                }
                finish:
                // echo $posFrom;
                $SelectResults = substr($sql, 7, $posFrom - 8);
                // echo "$SelectResults<br>";
                
                $SelectResults = explode(",", $SelectResults);
                // debug($SelectResults);

                for($i = 0; $i < count($SelectResults); $i++) {
                    if(str_contains($SelectResults[$i], " AS ")) {
                        $SelectResults[$i] = substr($SelectResults[$i], strpos($SelectResults[$i], " AS ") + 4);
                    }
                    $SelectResults[$i] = str_replace('\s', '', $SelectResults[$i]);
                    $SelectResults[$i] = trim($SelectResults[$i]);
                    $BindResults[] = $SelectResults[$i];
                }

                // echo $sql;
                // debug($BindResults);
                if(mysqli_stmt_bind_result($stmt, ...$BindResults)) {
                    $i = 0;
                    while(mysqli_stmt_fetch($stmt)) {
                        $j = 0;
                        foreach($BindResults as $Result) {
                            $results[$SelectResults[$j]][] = $Result;
                            $j++;
                        }
                        $i++;
                    }
                    mysqli_stmt_close($stmt);
                    // echo "####<br>";
                    return $results;
                } else {
                    fail("DB".$code."2", mysqli_error($connection));
                    return false;
                }
            } else {
                return true;
            }
        } else {
            fail("DB".$code."1", mysqli_error($connection));
            return false;
        }

    } else {
        fail("DB00", mysqli_error($connection));
        return false;
    }
}

// $type:   1 for print_r(), 0 or empty for var_dump()
function debug($var, int $type = 0) {
    echo "<pre>";
    if($type) {
        print_r($var);
    } else {
        var_dump($var);
    }
    echo "</pre>";
}

function checkNotifications($connection, int $userId) : array | bool {

    $sql = "SELECT Id
            FROM comment
            WHERE QuestionId IN (
                SELECT Id
                FROM question 
                WHERE Id IN (
                    SELECT QuestionId 
                    FROM comment
                ) AND AccountId = ?
            )";
    $allRepliedOwnQuestions = stmtExecute($connection, $sql, 1, "i", $userId);
    if(is_array($allRepliedOwnQuestions)) {
        $results["TotalQuestions"] = count($allRepliedOwnQuestions["Id"]);
    }

    $sql = "SELECT Id
            FROM comment
            WHERE QuestionId IN (
                SELECT QuestionId  
                FROM bookmark 
                WHERE QuestionId IN (
                    SELECT QuestionId 
                    FROM comment
                ) AND AccountId = ?
            )";
    $allRepliedBookmarkedQuestions = stmtExecute($connection, $sql, 1, "i", $userId);
    if(is_array($allRepliedBookmarkedQuestions)) {
        $results["TotalBookmarks"] = count($allRepliedBookmarkedQuestions["Id"]);
        $results["Bookmark"] = $allRepliedBookmarkedQuestions["Id"];
    }
    


    $sql = "INSERT IGNORE INTO notification (AccountId, CommentId)
            VALUES (?, ?)";

    for($k = 0; $k < 2; $k++) {
        $commentId = 0;
        if($k == 0 && is_array($allRepliedOwnQuestions)) {
            $commentId = $allRepliedOwnQuestions["Id"];
        } else {
            $k++;
            if(is_array($allRepliedBookmarkedQuestions)) {
                $commentId = $allRepliedBookmarkedQuestions["Id"];
            } else {
                goto end;
            }
        }
        for($i = 0; $i < count($commentId); $i++) {
            stmtExecute($connection, $sql, 1, "ii", $userId, $commentId[$i]);
        }
    }
    end:
    $sql = "SELECT Id
            FROM comment
            WHERE Id IN (
                SELECT CommentId
                FROM notification
                WHERE AccountId = ? AND isSeen = 0
            ) ORDER BY CommentDate DESC";
    $tmp = stmtExecute($connection, $sql, 1, "i", $userId);
    if(is_array($tmp)) {
        $results["All"] = $tmp["Id"];
    }

    if(isset($results)) {
        return $results;
    } else {
        return false;
    }
}

function setURL($state = null) : void {
    $path = $_SERVER["REQUEST_URI"];
    $path = str_replace("&reload", "", $path);

    switch($state) {
        case 'hidden':
            $old = 'show';
            break;
        default:
            $old = 'hidden';
            break;
    }
    $path = str_replace("Notifications=$old", "Notifications=$state", $path);

    if($state === null && !str_contains($path, ".php?")) {
        $path .= "?Notifications=$old";
    } else if($state === null && str_contains($path, ".php?")) {
        $path .= "&Notifications=$old";
    }
    header("Location: $path");
}
?>