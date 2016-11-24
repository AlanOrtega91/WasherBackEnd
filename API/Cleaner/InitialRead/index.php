<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $service  = new Service();
  $cleanerInfo = $cleaner->readCleanerData($token);
  $cleanerId = $cleanerInfo['idLavador'];
  $review = $service->readReviewForCleaner($cleanerId);
  $servicesHistory = $service->getHistory($cleanerId,2);
  echo json_encode(array("Status"=>"OK","User Info"=>$cleanerInfo,"History"=>$servicesHistory,
                         "Calificacion"=>$review));
} catch(cleanerNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR cleaner"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} 
?>