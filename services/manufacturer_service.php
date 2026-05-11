<?php
include_once('database_service.php');
include_once('responce_service.php');
function getAllManufacturers() {
   $sql = "SELECT * FROM `manufacturers`";
   $data = query($sql, 'GET');
   sendSuccess("Found All Manufacturers", 'GET', 200, $data);
}
?>
