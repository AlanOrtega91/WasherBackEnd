<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";

if (!isset($_POST['token'])  || !isset($_POST['userType']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
try{
  $token = SafeString::safe($_POST['token']);
  $userType = SafeString::safe($_POST['userType']);
  if ($clientType == 1){
  	$user  = new User();
  	$info = $user->userHasToken($token);
  } elseif ($clientType == 2){
  	$cleaner  = new Cleaner();
  	$info = $cleaner->userHasToken($token);
  }
  $service  = new Service();
  $info = $service->getActiveService($token,$userType);
  echo json_encode(array("Status"=>"OK","info"=>$info));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(serviceNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR service"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>