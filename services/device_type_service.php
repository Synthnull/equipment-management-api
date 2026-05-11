<?php
include_once('database_service.php'); 
include_once('responce_service.php');

function getAllDeviceTypes() {
   $sql = "SELECT * FROM `device_types`";
   $data = query($sql, 'GET');

   return $data;
}

function createNewDeviceType($body) {

   if(!isset($body['device_type_name']) || $body['device_type_name'] == "") {
      sendError("Invalid Request Body", 'POST', 400);
   }

   $deviceTypeName = $body['device_type_name'];
   
   $current_types = getAllDeviceTypes();
   
   foreach ($current_types as $types) {
      if($types['device_type_name'] == $deviceTypeName) {
         sendError("Device Type Already Exists", 'POST', 409);
      }
   }

   $sql="INSERT INTO `device_types` (`device_type_name`, `status_id`) VALUES ('$deviceTypeName', '1')";
   $data = query($sql, "POST");

   return $data;
}
?>
