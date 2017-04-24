<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']) || !isset($_POST['device']))
  die(json_encode(array("Status"=>"ERROR missing values")));

try{
  $token = SafeString::safe($_POST['token']);
  $device = SafeString::safe($_POST['device']);
  $user  = new User();
  $car  = new Car();
  $service  = new Service();
  $userInfo = $user->readUserData($token);
  $clientId = $userInfo['idCliente'];
  $user->saveDevice($clientId,$device);
  $carsList = $car->getCarsList($clientId);
  $servicesHistory = $service->getHistory($clientId,1);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo,
                         "carsList"=>$carsList,"History"=>$servicesHistory,"cards" => Payment::readClient($userInfo["ConektaId"])));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database".$e->getMessage()));
} catch(carsNotFoundException $e)
{
  echo json_encode(array("Status"=>"OK","carsList"=>null));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
} catch (errorReadingUserPayment $e) {
	echo json_encode(array("Status"=>"ERROR user payment"));
}

?>