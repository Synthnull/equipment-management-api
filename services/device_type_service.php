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
   $id = query($sql, 'POST');
   
   $data = [
      "device_type_id" => (string)$id,   
      "device_type_name" => $deviceTypeName,
      "status_id" => "1"
   ];

   return $data;
}

function modifyDeviceType($deviceTypeId, $body) {
   if(!isset($body->device_type_name) || trim($body->device_type_name) == "") {
      sendError("Invalid Request Body", 'PUT', 400);
   }

   if(!isset($body->status_id) || trim($body->status_id) == "") {
      sendError("Invalid Request Body", 'PUT', 400);
   }
      
   $deviceTypeName = $body->device_type_name;
   $status = $body->status_id;
   
   if(!validateDeviceTypeName($deviceTypeName)) {
      sendError("Manufacturer Name Invalid", 'PUT', 400);
   }

   $sql = "SELECT `device_type_id` FROM `device_types` WHERE `device_type_name`='$deviceTypeName' AND `device_type_id`!='$deviceTypeId'"; 
   $rowsAffected = query($sql, 'PUT');

   if($rowsAffected > 0) {
       sendError("Device Type Name Already Exists", 'PUT', 409);
   }

   $sql = "UPDATE `device_types` SET `device_type_name`='$deviceTypeName', `status_id`='$status' WHERE `device_type_id`='$deviceTypeId'";
   $rowsAffected = query($sql, 'PUT');

   if($rowsAffected <= 0) {
      sendError("No Device Type To Update", 'PUT', 204);
   }

   $data = [
      "device_type_id" => (string)$deviceTypeId,   
      "device_type_name" => $deviceTypeName,
      "status_id" => (string)$status
   ];

   return $data;
}
?>
