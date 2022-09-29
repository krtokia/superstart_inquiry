<?php
require_once "./common.php";

$date = $_POST['date'];
$title = $_POST['title'];
if(!$title) {
    $title = "벙";
}

$pw = $_POST['password'];
$pw_hash = hash("sha256", $pw);

$val = $_POST['val'];
$val = json_decode($val, TRUE);

if($_POST['update'] !== "") {
    $sql = "update test set bdt=?, title=?, json=?, pw=? where pKey=".$_POST['update'];
} else {
    $sql = "insert into test(bdt, title, json, pw) values (?,?,?,?)";
}

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ssss", $date, $title, json_encode($val), $pw_hash);

$stmt->execute();

$insert_id = $_POST['update'] === "" ? $stmt->insert_id : $_POST['update'];
$copyCode = true;

include_once "./board.php";
?>