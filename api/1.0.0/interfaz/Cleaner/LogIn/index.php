<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['device']))
  die(json_encode(array("Status"=>"ERROR missing values")));

try{
  $email = SafeString::safe($_POST['email']);
  $password = SafeString::safe($_POST['password']);
  $device = SafeString::safe($_POST['device']);
  $cleaner  = new Cleaner();
  $service  = new Service();
  
  $cleanerInfo = $cleaner->sendLogIn($email, $password);
  $cleanerId = $cleanerInfo['idLavador'];
  $cleaner->saveDevice($cleanerId,$device);
  $review = $service->readReviewForCleaner($cleanerId);
  $servicesHistory = $service->getHistory($cleanerId,2);
  echo json_encode(array("Status"=>"OK","User Info"=>$cleanerInfo,"History"=>$servicesHistory,
                         "Calificacion"=>$review));
} catch(cleanerNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user not found"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB".$e->getMessage()));
} 
?>