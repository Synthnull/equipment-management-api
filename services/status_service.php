<?php
include_once('database_service.php'); 
include_once('responce_service.php');
function getAllStatuses() {
   $sql = "SELECT * FROM `status`";
   $data = query($sql, 'GET');

   return $data;
}
?>
