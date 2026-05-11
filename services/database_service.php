<?php
include_once('responce_service.php');

function db_connect($db, $method){    
   $hostname = "localhost";
   $username = "";
   $password = "";
   
   $dblink = new mysqli($hostname, $username, $password, $db);
   
   if (mysqli_connect_error())
   {
     sendError("Database Connection Failed", 500, $method); 
     die();
   }
   
   return $dblink;
}

function query($sql, $method) {
   $dblink = db_connect("equipment", $method);
   $result = $dblink->query($sql) or queryFail($method);

   if($method == "GET") {
      $res = $result->fetch_all(MYSQLI_ASSOC);
   } else {
      $res = $dblink->insert_id; 
   }   
   #free mysql connection
   $result->free(); 
   $dblink->close();

   return $res;
}

function queryFail($method) {
   sendError("Invalid SQL", 500, $method);
   die(); 
}
?>
