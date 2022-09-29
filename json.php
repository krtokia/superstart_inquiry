<?php
session_start();

$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);
$json_data = json_encode($json, JSON_UNESCAPED_UNICODE);


// $filename = $_SESSION['logedin'] . "__" . date("Ymd_His") . ".json";
// $filepath = "python/jsons";



$json_data_u = $json_data;
$json_data_u = preg_replace("/^\"/", "", $json_data_u);
$json_data_u = preg_replace("/\"$/", "", $json_data_u);
$json_data_u = str_replace("\\\/", "/", $json_data_u);
$json_data_u = str_replace("\\\"", "\"", $json_data_u);

$mysqli = new mysqli('host.docker.internal', 'ss_inquiry', '2017tbtm!1004', 'superstart_inquiry', 3306) or die("Cannot connet");

$id = $_SESSION['logedin'];
$title = trim($json['title']);
$domain = trim($json['domain']);

$sql = "INSERT INTO queries (author, title, domain, jsquery) VALUES (?,?,?,?)";
$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ssss", $id, $title, $domain, $json_data_u);

$stmt->execute();

echo $stmt->error;

$mysqli->close();

// file_put_contents($filepath . "/" . $filename, $json_data_u);



// echo $filepath . "/" . $filename;
