<?php
header('Content-Type: application/json; charset=UTF-8');
header('HTTP/1.1 200 OK');
$url = $_SERVER['REQUEST_URI'];
$reqPayload = explode("/",trim($url,"/"));
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $reqPayload[1];

#endpoints
switch ($endpoint) {
   case 'list_manufacturers':
      include('services/manufacturer_service/list_manufacturers.php');
      break;
   case 'list_device_types':
      break;
   case 'add_device_type':
      break;
   case 'add_manufacturer':
      break;
   case 'add_equipment':
      break;
   case 'search_by_device_type':
      break;
   case 'search_by_manufacturer':
      break;
   case 'search_by_device_type':
      break;
   case 'search_by_serial_number':
      break;
   case 'modify_equipment_by_id':
      break;
   case 'modify_device_type_by_id':
      break;
   case 'modify_manufacturer_by_id':
   default:
      sendError("Requested Endpoint Does Not Exist", $method); 
      break;
}
die();

function sendError($message, $method) {
   $resArr = array("status"=>"Error", "message" => $message,"data"=>"", "method"=>$method);
   $res = json_encode($resArr);
   echo $res;
}
?>
