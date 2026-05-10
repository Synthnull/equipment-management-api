<?php
header('Content-Type: application/json; charset=UTF-8');
header('HTTP/1.1 200 OK');
$url = $_SERVER['REQUEST_URI'];
$data = explode("/",trim($url,"/"));
$method = $_SERVER['REQUEST_METHOD'];

$resArr = array("status"=>"Success", "message" => "hello world!","data"=>$data, "method"=>$method);

$res = json_encode($resArr);
echo $res;
?>
