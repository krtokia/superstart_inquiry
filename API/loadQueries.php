<?php
require_once("./common.php");

$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);

$action = $json['action'];

if($action == "check") {
    $sql = "SELECT pKey FROM queries WHERE author = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $id);
    $id = $_SESSION['logedin'];
    $stmt->execute();
    $result = $stmt->get_result();
    echo $result->num_rows;
} else if($action == "list") {
    $sql = "SELECT pKey, title, DATE_FORMAT(cdt, '%Y-%m-%d %H:%i') cdt FROM queries WHERE author = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $id);
    $id = $_SESSION['logedin'];
    $stmt->execute();
    $result = $stmt->get_result();
    $res = [];
    while($row = $result->fetch_assoc()) {
        $res[] = $row;
    }
    echo json_encode($res);
} else if($action == "get") {
    $sql = "SELECT jsquery FROM queries WHERE pKey = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $pKey);
    $pKey = $json['pKey'];
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo json_encode($row);
}



$mysqli->close();