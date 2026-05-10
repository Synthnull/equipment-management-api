<?php
header('Content-Type: application/json; charset=UTF-8');
header('HTTP/1.1 200 OK');
include_once('services/error_service.php');
$url = $_SERVER['REQUEST_URI'];
$reqPayload = explode("/",trim($url,"/"));
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $reqPayload[1];

#endpoints
switch ($endpoint) {
   case 'list_manufacturers':
      if($method != "GET") {
      
      }
      include('services/manufacturer_service/list_manufacturers.php');
      break;
   case 'list_device_types':
      if($method != "GET") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'add_device_type':
      if($method != "POST") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'add_manufacturer':
      if($method != "POST") {
         sendError("Invalid method", $method, 401); 
      } 
      break;
   case 'add_equipment':
      break;
   case 'search_by_device_type':
      if($method != "GET") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'search_by_manufacturer':
      if($method != "GET") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'search_by_device_type':
      if($method != "GET") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'search_by_serial_number':
      if($method != "GET") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'modify_equipment_by_id':
      if($method != "PATCH") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'modify_device_type_by_id':
      if($method != "PATCH") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   case 'modify_manufacturer_by_id':
      if($method != "PATCH") {
         sendError("Invalid method", $method, 401); 
      }
      break;
   default:
      sendError("Requested Endpoint Does Not Exist", $method, 404); 
      break;
}
die();
?>
