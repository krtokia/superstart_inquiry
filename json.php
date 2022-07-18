<?php

$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);
$json_data = json_encode($json, JSON_UNESCAPED_UNICODE);


$filename = $_SESSION['logedin'] . "__" . date("Ymd_His") . ".json";
$filepath = "python/jsons";

$json_data_u = $json_data;
$json_data_u = preg_replace("/^\"/", "", $json_data_u);
$json_data_u = preg_replace("/\"$/", "", $json_data_u);
$json_data_u = str_replace("\\\"", "\"", $json_data_u);



file_put_contents($filepath . "/" . $filename, $json_data_u);



echo $filepath . "/" . $filename;
