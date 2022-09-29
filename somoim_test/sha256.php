<?php
$pw = $_POST['pw'];
$pw_hash = hash("sha256", $pw);

echo $pw_hash;