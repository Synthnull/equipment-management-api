<?php

function partSerialNumber(string $fullSerialNumber, &$prefix, &$delimeter, &$body) : void {
   if($fullSerialNumber == null) {
      return;
   }

   $prefix = substr($fullSerialNumber, 0,2);
   $delimeter = $fullSerialNumber[2];
   $body = substr($fullSerialNumber, 3);
}

function validateSerialNumber(&$prefix, &$body, $serialNumber) : bool {
   $delimeter = "";
   partSerialNumber($serialNumber, $prefix, $delimeter, $body);

   //incorect size
   if(strlen($body) != 64) {
      return true;
   }

   //incorect delimiter
   if($delimeter != '-') {
      return true;
   }

   return false;
}

function validateManufacturerName($manufacturerName) : bool {
   if(!preg_match('/^[A-Z][a-z\s]+$/', $manufacturerName)) {
      return false;
   }

   return true;
}

function validateDeviceTypeName($deviceTypeName) {
   if(!preg_match('/^[a-z\s]+$/', $deviceTypeName)) {
      return false;
   }

   return true;
}
?>
