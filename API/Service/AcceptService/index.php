<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['serviceId']) || !isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{ 
  $serviceId = SafeString::safe($_POST['serviceId']);
  $token = SafeString::safe($_POST['token']);
  $cleaner  = new Cleaner();
  $info = $cleaner->userHasToken($token);
  
  $service  = new Service();
  $info = $service->acceptService($serviceId,$info['idLavador'],$token);
  echo json_encode(array("Status"=>"OK", "service info" => $info));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(serviceTakenException $e)
{
  echo json_encode(array("Status"=>"ERROR service"));
} catch (insufficientProductException $e)
{
  echo json_encode(array("Status"=>"ERROR faltan productos"));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>