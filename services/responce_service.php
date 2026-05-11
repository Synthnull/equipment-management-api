<?php
function sendError($message, $method, $code) {
   http_response_code($code);
   $resArr = array("status"=>"Error", "message" => $message,"data"=>"", "method"=>$method);
   $res = json_encode($resArr);
   echo $res;
   die();
}

function sendSuccess($message, $method, $code, $data) {
   http_response_code($code);
   $resArr = array("status"=>"Success", "message" => $message, "data"=>$data, "method"=>$method);
   $res = json_encode($resArr);
   echo $res;
   die();
}
?>
