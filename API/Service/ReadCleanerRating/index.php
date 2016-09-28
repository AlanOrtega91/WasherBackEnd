<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_POST['token']) || !isset($_POST['idLavador']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
  $token = SafeString::safe($_POST['token']);
  $cleanerId =  SafeString::safe($_POST['idLavador']);
  $service = new Service();
  $user  = new User();
  $user->userHasToken($token);
  $review = $service->readReviewForCleaner($cleanerId);
  
  echo json_encode(array("Status"=>"OK","Calificacion"=>$review));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}
catch (cleanerNotFoundException $e) {
	echo json_encode(array("Status" => "ERROR CLEANER"));
}

?>