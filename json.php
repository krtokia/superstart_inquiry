<?php

$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);
$json_data = json_encode($json, JSON_UNESCAPED_UNICODE);
file_put_contents('myfile3.json', $json_data);
