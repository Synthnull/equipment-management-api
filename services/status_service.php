<?php
include_once('database_service.php'); 
include_once('responce_service.php');
function getAllStatuses() {
   $sql = "SELECT * FROM `status`";
   $data = query($sql, 'GET');
   sendSuccess("Found All Device Types", 'GET', 200, $data);
}
?>
