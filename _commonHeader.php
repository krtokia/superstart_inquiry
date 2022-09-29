<?php

$logedin_info = $_SESSION['logedin'];

if (!$logedin_info) {
    echo "<script type='text/javascript'>location.replace('./login.php');</script>";
    exit();
}
?>