<?php

$sql = "SELECT question.Id AS Id, question.Title AS Title, question.AskDate AS AskDate, (SELECT COUNT(bookmark.QuestionId) FROM bookmark WHERE bookmark.QuestionId = question.Id) AS Bookmarks FROM question ORDER BY Bookmarks DESC, AskDate DESC;";
$questions = stmtExecute($conn, $sql, 2);

// $sql = "SELECT question.Id AS Id, question.Title AS Title, question.AskDate AS AskDate, (SELECT COUNT(bookmark.QuestionId) FROM bookmark WHERE bookmark.QuestionId = question.Id) AS Bookmarks, (SELECT Username FROM account WHERE Id = 2) AS Username FROM question ORDER BY Bookmarks DESC, AskDate DESC;";
// $test = stmtExecute($conn, $sql, 2);

// debug($test);

echo "<aside>
        <h2>Top questions</h2>
        <div class='container'>";

        foreach ($questions["Title"] as $index => $title) {

            $askDate = $questions["AskDate"][$index];
            $id = $questions["Id"][$index];
            echo "<div class='aside-element'>
                    <a class='aside-element-title' href='questions.php?TitleId=$id'>$title</a>
                    <div class='tags'>";
                    $sql = "SELECT SubCategory FROM subtag WHERE Id IN (SELECT SubTagId FROM tag_question WHERE QuestionId = ?)";

                    $tags = stmtExecute($conn, $sql, 1, "i", $id);
                    foreach($tags["SubCategory"] as $index => $TagName) {
                        echo "<p>$TagName</p>";
                    }
                    echo "</div>
                    <p class='age'>".calculateDate($askDate)." ago</p>
                </div>";
        } 
        echo "</div>
    </aside>";

?>