<?php
require_once("./common.php");
$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);

$pKey = $json['pKey'];

$action = $json['action'];

if($action == "delete") {
    $sql = "DELETE FROM queries WHERE pKey = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $pKey);
    $stmt->execute();

} else if($action == "download") {
    $sql = "SELECT * FROM queries WHERE pKey = $pKey";
    $result = $mysqli->query($sql);

    if(!$result) {
        echo "Error";
        $mysqli->close();
        exit(0);
    }
    $row = $result->fetch_assoc();

    $returnValue = array();
    $returnValue['filename'] = str_replace(" ", "_", $row['title']);

    if($json['type'] == "excel") {
        $python_path = $_SERVER['DOCUMENT_ROOT'].'/python';
        // $output = exec('nohup python3 '.$python_path.'/excel.py '.$pKey.' >>'.$python_path.'/log/'.date('Y-m-d').'.log 2>&1 & printf "%u" $!');
        $output = exec('python3 '.$python_path.'/excel.py '.$row['author'].'_'.$row['pKey'].' 2>&1');
        $returnValue['href'] = "/python/results/".$row['author'].'_'.$row['pKey'].".xlsx";
        $returnValue['filename'] = $returnValue['filename'].".xlsx";
    } else if($json['type'] == "json") {
        $returnValue['href'] = "/python/jsons/".$row['author'].'_'.$row['pKey'].".json";
        $returnValue['filename'] = $returnValue['filename'].".json";
    }
    echo json_encode($returnValue);
} else if($action == "execute") {
    $s = $json['s'];
    $e = $json['e'];

    $sql = "UPDATE queries SET status = 1, pageStart = $s, pageEnd = $e WHERE pKey = $pKey";
    $result = $mysqli->query($sql);
    $sql = "SELECT * FROM queries WHERE pKey = $pKey";
    $result = $mysqli->query($sql);

    if(!$result) {
        echo "Error";
        $mysqli->close();
        exit(0);
    }
    $python_path = $_SERVER['DOCUMENT_ROOT'].'/python';

    $output = exec('nohup python3 '.$python_path.'/main.py '.$pKey.' >> /dev/null 2>&1 & printf "%u" $!');
    
    // echo $output;
    // echo 'nohup python3 '.$python_path.'/main.py '.$pKey;
    var_dump($output);
} else if($action == "changeName") {
    $name = $json['name'];
    $sql = "UPDATE queries SET title = ? WHERE pKey = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $name, $pKey);
    $stmt->execute();
    
} else if($action == "listInfo") {
    $sql = "SELECT * FROM queries WHERE pKey = $pKey";
    $result = $mysqli->query($sql);

    if(!$result) {
        echo "Error";
        $mysqli->close();
        exit(0);
    }
    $row = $result->fetch_assoc();
    $res = array("isList" => $row['isList'], "pageStart" => $row['pageStart'], "pageEnd" => $row['pageEnd']);
    echo json_encode($res);
}

$mysqli->close();