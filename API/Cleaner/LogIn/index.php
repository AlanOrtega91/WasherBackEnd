<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/Cleaner.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['mail']) || !isset($_POST['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
  $mail = SafeString::safe($_POST['mail']);
  $password = SafeString::safe($_POST['password']);
  $cleaner  = new Cleaner();
  $service  = new Service();
  $cleanerInfo = $cleaner->sendLogIn($mail, $password);
  $cleanerId = $cleanerInfo['idLavador'];
  $review = $service->readReviewForCleaner($cleanerId);
  $servicesHistory = $service->getHistory($cleanerId,2);
  echo json_encode(array("Status"=>"OK","User Info"=>$cleanerInfo,"History"=>$servicesHistory,
                         "Calificacion"=>$review));
} catch(cleanerNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user not found"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>