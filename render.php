<?php
$json = file_get_contents("php://input");
$json = json_decode($json, TRUE);

$geturl = $json['url'];

$url = "http://host.docker.internal:8050/render.html";
$postFileds = array(
    "html" => 1,
    "timeout" => 10,
    "wait" => 1,
    "url" => $geturl
);

$postFileds = json_encode($postFileds);

$ch = curl_init();                                 //curl 초기화
curl_setopt($ch, CURLOPT_URL, $url);               //URL 지정하기
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFileds);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함

$response = curl_exec($ch);

curl_close($ch);

echo $response;
