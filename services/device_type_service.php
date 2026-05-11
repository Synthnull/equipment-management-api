<?php
include_once('database_service.php'); 
include_once('responce_service.php');
include_once('data_validation_service.php');

function getAllDeviceTypes() {
   $sql = "SELECT * FROM `device_types`";
   $data = query($sql, 'GET');

   return $data;
}

function createNewDeviceType($body) {

   if(!isset($body->device_type_name) || trim($body->device_type_name) == "") {
      sendError("Invalid Request Body", 'POST', 400);
   }

   $deviceTypeName = $body->device_type_name;

   if(!validateDeviceTypeName($deviceTypeName)) {
      sendError("Device Type Name Invalid", 'POST', 409);
   }

   $current_types = getAllDeviceTypes();
   
   foreach ($current_types as $types) {
      if($types['device_type_name'] == $deviceTypeName) {
         sendError("Device Type Already Exists", 'POST', 409);
      }
   }

   $sql="INSERT INTO `device_types` (`device_type_name`, `status_id`) VALUES ('$deviceTypeName', '1')";
   $id = query($sql, "POST");
   
   $data = [
      "device_type_id" => (string)$id,   
      "device_type_name" => $deviceTypeName,
      "status_id" => "1"
   ];

   return $data;
}
?>
