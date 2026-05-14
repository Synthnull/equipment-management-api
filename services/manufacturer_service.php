<?php
include_once('database_service.php');
include_once('responce_service.php');
function getAllManufacturers() {
   $sql = "SELECT * FROM `manufacturers`";
   $data = query($sql, 'GET');

   return $data;
}

function createNewManufacturer($body) {

   if(!isset($body->manufacturer_name) || trim($body->manufacturer_name) == "") {
      sendError("Invalid Request Body", 'POST', 400);
   }

   $manufacturerName = $body->manufacturer_name;
   
   if(!validateManufacturerName($manufacturerName)) {
      sendError("Manufacturer Name Invalid", 'POST', 409);
   }

   $current_manufacturers = getAllManufacturers();
   
   foreach ($current_manufacturers as $manufacturer) {
      if($manufacturer['manufacturer_name'] == $manufacturerName) {
         sendError("Manufacturer Already Exists", 'POST', 409);
      }
   }

   $sql = "INSERT INTO `manufacturers` (`manufacturer_name`, `status_id`) VALUES ('$manufacturerName', '1')";
   $id = query($sql, "POST");
   
   $data = [
      "manufacturer_id" => (string)$id,   
      "manufacturer_name" => $manufacturerName,
      "status_id" => "1"
   ];

   return $data;
}

function modifyManufacturer($manufacturerId, $body) {
   if(!isset($body->manufacturer_name) || trim($body->manufacturer_name) == "") {
      sendError("Invalid Request Body", 'PUT', 400);
   }

   if(!isset($body->status_id) || trim($body->status_id) == "") {
      sendError("Invalid Request Body", 'PUT', 400);
   }
      
   $manufacturerName = $body->manufacturer_name;
   $status = $body->status_id;
   
   if(!validateManufacturerName($manufacturerName)) {
      sendError("Manufacturer Name Invalid", 'PUT', 400);
   }

   $sql = "SELECT `manufacturer_id` FROM `manufacturers` WHERE `manufacturer_name`='$manufacturerName' and `manufacturer_id`!='$manufacturerId'";
   $rowsAffected = query($sql, 'PUT');

   if($rowsAffected > 0) {
       sendError("Manufacturer Name Already Exists", 'PUT', 409);
   }
   
   $sql = "UPDATE `manufacturers` SET `status_id`='$status' WHERE `manufacturer_id`='$manufacturerId'";
   $rowsAffected = query($sql, 'PUT');

   if($rowsAffected <= 0) {
      sendError("No Manufacturer To Update", 'PUT', 204);
   }

   $data = [
      "manufacturer_id" => (string)$manufacturerId,   
      "manufacturer_name" => $manufacturerName,
      "status_id" => (string)$status
   ];

   return $data;
}
?>
