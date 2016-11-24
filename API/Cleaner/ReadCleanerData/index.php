<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $cleanerInfo = $cleaner->readCleanerData($token);
  echo json_encode(array("Status"=>"OK","Cleaner Info"=>$cleanerInfo));
} catch(sessionNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(cleanerNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR cleaner"));
}
?>