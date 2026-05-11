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

   $sql="Insert into `device_types` (`device_type_name`, `status_id`) values ('$deviceTypeName', '1')";
}
?>
