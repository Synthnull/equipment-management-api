<?php
header('Content-Type: application/json; charset=UTF-8');
header('HTTP/1.1 200 OK');

include_once('services/responce_service.php');
include_once('services/device_type_service.php');
include_once('services/manufacturer_service.php');
include_once('services/equipment_service.php');
include_once('services/status_service.php');

$url = $_SERVER['REQUEST_URI'];
$reqUrl = explode("/",trim($url,"/"));
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $reqUrl[1];
$reqPayload = file_get_contents('php://input'); #read the request body
$reqData = json_decode($reqPayload);

#endpoints
switch ($endpoint) {
   case 'get_manufacturers':
      if($method != "GET") {
         sendError("Method Not Allowed", $method, 405);
      }
      $data = getAllManufacturers();
      sendSuccess("Found All Manufacturers", 'GET', 200, $data);
      break;
   case 'get_device_types':
      if($method != "GET") {
         sendError("Method Not Allowed", $method, 405); 
      }
      $data = getAllDeviceTypes();
      sendSuccess("Found All Device Types", 'GET', 200, $data);
      break;
   case 'get_statuses':
      if($method != "GET") {
         sendError("Method Not Allowed", $method, 405); 
      }
      $data = getAllStatuses();
      sendSuccess("Found All Statues", 'GET', 200, $data);
      break;
   case 'add_device_type':
      if($method != "POST") {
         sendError("Method Not Allowed", $method, 405); 
      }
      $data = createNewDeviceType($reqData);
      sendSuccess("Successfully Created New Device Type", 'POST', 201, $data);
      break;
   case 'add_manufacturer':
      if($method != "POST") {
         sendError("Method Not Allowed", $method, 405); 
      }
      $data = createNewManufacturer($reqData);
      sendSuccess("Successfully Created New Manufacturer", 'POST', 201, $data);
      break;
   case 'add_equipment':
      if($method != "POST") {
         sendError("Method Not Allowed", $method, 405); 
      }

      $data = createNewEquipment($reqData);
      sendSuccess("Successfully Created New Equipment", 'POST', 201, $data);   
      break;
   case 'search_equipment':
      if($method != "GET") {
         sendError("Method Not Allowed", $method, 405); 
      }

      $data = searchEquipment($reqData);
      sendSuccess("Successfully Searched Equipment", 'GET', 200, $data);
      break;
   case 'modify_equipment_by_id':
      if($method != "PUT") {
         sendError("Method Not Allowed", $method, 405); 
      }
      if(count($reqUrl) != 3) {
         sendError("Missing Equipment Id In URL", 'PUT', 400);
      }
      $equipmentId = $reqUrl[2];

      $data = modifyEquipment($equipmentId, $reqData);
      sendSuccess("Successfully Modified Equipment", 'PUT', 200, $data);
      break;
   case 'modify_device_type_by_id':
      if($method != "PUT") {
         sendError("Method Not Allowed", $method, 405); 
      }

      if(count($reqUrl) != 3) {
         sendError("Missing Device Type Id In URL", 'PUT', 400);
      }
      $deviceTypeId = $reqUrl[2];

      $data = modifyDeviceType($deviceTypeId, $reqData);
      sendSuccess("Successfully Modified Device Type", 'PUT', 200);
      break;
   case 'modify_manufacturer_by_id':
      if($method != "PUT") {
         sendError("Method Not Allowed", $method, 405); 
      }

      if(count($reqUrl) != 3) {
         sendError("Missing Manufacturer Id In URL", 'PUT', 400);
      }
      $manufacturerId = $reqUrl[2];

      $data = modifyManufacturer($manufacturerId, $reqData);
      sendSuccess("Successfully Modified Manufacturer", 'PUT', 200, $data);
      break;
   default:
      sendError("Requested Endpoint Does Not Exist", $method, 404); 
      break;
}
die();
?>
