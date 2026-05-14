<?php
include_once('database_service.php');
include_once('responce_service.php');
function createNewEquipment($body) {
   $bodyInvalid = false;

   if(!isset($body->device_type_id) || trim($body->device_type_id) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->manufacturer_id) || trim($body->manufacturer_id) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->serial_number) || trim($body->serial_number) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->status_id) || trim($body->status_id) == "") {
      $bodyInvalid = true;
   }

   if($bodyInvalid) {
      sendError("One Or More Parameters Are Missing From The Request", 'POST', 400);
   }
   
   $deviceType = $body->device_type_id;
   $manufacturer = $body->manufacturer_id;
   $serialNumber = $body->serial_number;
   $statusId = $body->status_id;

   $serialBody = "";
   $serialPrefix= "";

   if(validateSerialNumber($serialPrefix, $serialBody, $serialNumber)) {
      sendError("Invalid Serial Number", 'POST', 400);
   }

   $sql = "SELECT `device_id` FROM `devices` WHERE `serial_number_body`='$serialBody' AND `serial_number_prefix`='$serialPrefix'";
   $data = query($sql, "GET");

   if(count($data) > 0) {
      sendError("Device already Exists", 'POST', 409); 
   }

   $sql = "INSERT INTO `devices` (`device_type_id`,`manufacturer_id`, `serial_number_prefix`, `serial_number_body`, `status_id`) VALUES ('$deviceType','$manufacturer','$serialPrefix','$serialBody', '$statusId')";
   $id = query($sql, 'POST');
      
   $data = [
      "device_id" => (string)$id,
      "device_type_id" => (string)$deviceType,
      "manufacturer_id" => (string)$manufacturer,   
      "serial_number_prefix" => $serialPrefix,
      "serial_number_body" => $serialBody,
      "status_id" => (string)$statusId,
   ];

   return $data;
}

function searchEquipment($body) {
   if(!isset($body->device_type_id) || trim($body->device_type_id) == "") {
      $deviceType = 0;
   }else {
      $deviceType = $body->device_type_id;
   }

   if(!isset($body->manufacturer_id) || trim($body->manufacturer_id) == "") {
      $manufacturer = 0;
   } else {
      $manufacturer = $body->manufacturer_id;
   }

   if(!isset($body->serial_number) || trim($body->serial_number) == "") {
      $serialNumber = 0;
   } else {
      $serialNumber = $body->serial_number;
   }

   if(!isset($body->status_id) || trim($body->status_id) == "") {
      $status = 0;
   } else {
      $status = $body->status_id;
   }

   $serialPrefix = "";
   $serialBody = "";
   if($serialNumber) {
      validateSerialNumber($serialPrefix, $serialBody, $serialNumber);
   }
   $sql = 'SELECT
   d.device_id,
   d.status_id,
   m.manufacturer_name, 
   dt.device_type_name, 
   d.serial_number_prefix, 
   d.serial_number_body,
   s.status_name
   FROM devices AS d';

   $sql .= ' JOIN manufacturers AS m ON d.manufacturer_id = m.manufacturer_id
   JOIN device_types AS dt ON d.device_type_id = dt.device_type_id
   JOIN status AS s ON d.status_id = s.status_id
   WHERE 1=1';

   if($deviceType != 0) {
     $sql .= " AND d.device_type_id='$deviceType' AND dt.status_id = '1'";
   }else {
     $sql .= " AND dt.status_id='1'";
   }

   if($manufacturer != 0) {
     $sql .= " AND d.manufacturer_id='$manufacturer' AND m.status_id = '1'";
   } else {
     $sql .= " AND m.status_id='1'";
   }

   if($serialNumber) {
     $sql .= " AND d.serial_number_body='$serialBody' AND d.serial_number_prefix='$serialPrefix'";
   }
   if($status != 0) {
     $sql .= " AND d.status_id='$status'";
   }
   
   $sql .= " LIMIT 1000 ";
   
   $data = query($sql, 'GET');

   return $data;
}

function modifyEquipmentById($equipmentId, $body) {
   $bodyInvalid = false;

   if(!isset($body->device_type_id) || trim($body->device_type_id) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->manufacturer_id) || trim($body->manufacturer_id) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->serial_number) || trim($body->serial_number) == "") {
      $bodyInvalid = true;
   }

   if(!isset($body->status_id) || trim($body->status_id) == "") {
      $bodyInvalid = true;
   }

   if($bodyInvalid) {
      sendError("One Or More Parameters Are Missing From The Request", 'PUT', 400);
   }
   
   $deviceType = $body->device_type_id;
   $manufacturer = $body->device_type_id;
   $serialNumber = $body->serial_number;
   $statusId = $body->status_id;
   
   $serialPrefix = "";
   $serialBody = "";

   if(validateSerialNumber($serialPrefix, $serialBody, $serialNumber)) {
      sendError("Invalid Serial Number", 'PUT', 400);
   }

   $sql="SELECT `device_id` FROM `devices` where `serial_number_body`='$serialBody' AND `serial_number_prefix`='$serialPrefix' AND `device_id` !='" . $equipmentId . "'";
   $data = query($sql, 'GET');
   if (count($data) > 0) { 
      sendError("Serial Number is previously taken", "PUT", 409);
   }

   $sql="UPDATE `devices` SET 
        `device_type_id` = '$deviceType',
        `manufacturer_id` = '$manufacturer',
        `status_id` = '$statusId',
        `serial_number_prefix` = '$serialPrefix',
        `serial_number_body` = '$serialBody'
        WHERE `device_id` ='" . $equipmentId . "'";
   
   $affectedRows = query($sql, "PUT");

   if($affectedRows <= 0) {
      sendError("No Equipment Updated", 'PUT', 204);
   }

   $data = [
      "device_id" => (string)$equipmentId,
      "device_type_id" => (string)$deviceType,
      "manufacturer_id" => (string)$manufacturer,   
      "serial_number_prefix" => $serialPrefix,
      "serial_number_body" => $serialBody,
      "status_id" => (string)$statusId
   ];

   return $data;
}

function getEquipmentById($equipmentId) {
   $sql = 'SELECT
   d.device_id,
   d.status_id,
   m.manufacturer_name, 
   dt.device_type_name, 
   d.serial_number_prefix, 
   d.serial_number_body,
   s.status_name
   FROM devices AS d';

   $sql .= ' JOIN manufacturers AS m ON d.manufacturer_id = m.manufacturer_id
   JOIN device_types AS dt ON d.device_type_id = dt.device_type_id
   JOIN status AS s ON d.status_id = s.status_id
   WHERE d.device_id=' . $equipmentId;

   $data = query($sql, 'GET');
   
   return $data;
}
?>
