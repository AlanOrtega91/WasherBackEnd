<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$token = SafeString::safe($_POST['token']);
try{
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