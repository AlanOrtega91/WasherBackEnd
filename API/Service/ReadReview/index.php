<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $info = $cleaner->userHasToken($token);
  $service  = new Service();
  $review = $service->readReviewForCleaner($info['idLavador']);
  echo json_encode(array("Status"=>"OK","Calificacion"=>$review));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>