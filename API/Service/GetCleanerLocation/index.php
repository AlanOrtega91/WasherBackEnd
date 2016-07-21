<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['cleanerId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{  
  $cleanerId = SafeString::safe($_POST['cleanerId']);

  $service  = new Service();
  $cleaner = $service->getCleanerLocation($cleanerId);
  echo json_encode(array("Status"=>"OK","cleaner" => $cleaner));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>