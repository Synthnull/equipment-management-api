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
   
   $current_manufacturers = getAllManufacturers();
   
   foreach ($current_manufacturers as $manufacturer) {
      if($manufacturer['manufacturer_name'] == $manufacturerName) {
         sendError("Manufacturer Already Exists", 'POST', 409);
      }
   }

   $sql="INSERT INTO `manufacturers` (`manufacturer_name`, `status_id`) VALUES ('$manufacturerName', '1')";
   $id = query($sql, "POST");
   
   $data = [
      "manufacturer_id" => (string)$id,   
      "manufacturer_name" => $manufacturerName,
      "status_id" => "1"
   ];

   return $data;
}
?>
