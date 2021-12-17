<?php

// Calculate Time Difference
function calculateDate($timestamp) {
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
// @Param $sql: Don't use spaces between the commas in your statement                                   //
//                                                                                                      //
//  Example:                                                                                            //
//                                                                                                      //
//  "SELECT Title,AskDate FROM question ORDER BY AskDate DESC"                                          //
//               ^                                                                                      //
//               |                                                                                      //
//                                                                                                      //
// By:          Joris Hummel                                                                            //
//                                                                                                      //
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function stmtExecute($connection, string $sql, int $code, string $ParamChars = NULL, ...$BindParamVars) : array {

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
                                } 
                            } else {
                                fail("DB".$code."1", substr_count($sql, "?"));
                            }
                        } else {
                            fail("DB".$code."0");
                        }
                    } else {
                        fail("DB".$code."3", substr_count($sql, "?"));
                    }
                } else {
                    fail("DB".$code."5");
                }
            } else {
                fail("DB".$code."2");
            }
        }  

        $sql = str_replace("&nbsp;", "", $sql);
        $SelectResults = substr($sql, 7, strpos($sql, "FROM") - 8);
        $SelectResults = explode(",", $SelectResults);
        foreach($SelectResults as $BindParamResult) {
            $BindResults[] = $BindParamResult;
        }

        if(mysqli_stmt_execute($stmt)) {
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
                return $results;
            } else {
                fail("DB".$code."2", mysqli_error($connection));
            }
        } else {
            fail("DB".$code."1", mysqli_error($connection));
        }

    } else {
        fail("DB00", mysqli_error($connection));
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

?>