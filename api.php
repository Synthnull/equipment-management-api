<?php
header('Content-Type: application/json; charset=UTF-8');
header('HTTP/1.1 200 OK');
$url=$_SERVER['REQUEST_URI'];

$resArr=array("status"=>"Success", "message" => "hello world!","data"=>$url);

$res=json_encode($resArr);
echo $res;
?>
