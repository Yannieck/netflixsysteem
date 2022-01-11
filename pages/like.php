<?php
if(isset($_GET['page'])){
    echo $_GET['page'];
} else {
    echo "Error 404: page not found";
}