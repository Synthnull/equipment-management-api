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
      sendError("One Or More Parameters Are Missing From The Request", "POST", 400);
   }
   
   $deviceType = $body->device_type_id;
   $manufacturer = $body->manufacturer_id;
   $serialNumber = $body->serial_number;
   $statusId = $body->status_id;

   $serialBody = "";
   $serialPrefix= "";

   if(validateSerialNumber($serialPrefix, $serialBody, $serialNumber)) {
      sendError("Invalid Serial Number", "POST", 400);
   }

   $sql = "SELECT `device_id` FROM `devices` WHERE `serial_number_body`='$serialBody' AND `serial_number_prefix`='$serialPrefix'";
   $data = query($sql, "GET"); #TODO: internally call the search service when its created

   if(count($data) > 0) {
      sendError("Device already Exists", "POST", 409); 
   }

   $sql = "INSERT INTO `devices` (`device_type_id`,`manufacturer_id`, `serial_number_prefix`, `serial_number_body`) VALUES ('$deviceType','$manufacturer','$serialPrefix','$serialBody')";
   $id = query($sql, "POST");
      
   $data = [
      "device_type_id" => (string)$deviceType,
      "manufacturer_id" => (string)$manufacturer,   
      "serial_number_prefix" => $serialPrefix,
      "serial_number_body" => $serialBody,
      "status_id" => (string)$statusId,
   ];

   return $data;
}
?>
