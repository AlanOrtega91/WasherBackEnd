<?php
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/Service.php";

if (!isset($_GET['serviceId']) || !isset($_GET['cleanerId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$serviceId = SafeString::safe($_GET['serviceId']);
$cleanerId = SafeString::safe($_GET['cleanerId']);
try{
  $service  = new Service();
  $service->acceptService($serviceId,$cleanerId);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch(serviceTakenException $e)
{
  echo json_encode(array("Status"=>"ERROR service"));
} catch (insufficientProductException $e)
{
  echo json_encode(array("Status"=>"ERROR faltan productos"));
}
?>