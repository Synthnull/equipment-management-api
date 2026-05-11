<?php
include_once('database_service.php'); 
include_once('responce_service.php');
function getAllDeviceTypes() {
   $sql = "SELECT * FROM `device_types`";
   $data = query($sql, 'GET');
   sendSuccess("Found All Device Types", 'GET', 200, $data);
}
?>
