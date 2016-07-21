<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Cleaner.php";

if (!isset($_POST['token']) || !isset($_POST['productId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $token = SafeString::safe($_POST['token']);
  $productId = SafeString::safe($_POST['productId']);
  $cleaner = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $product  = new Product();
  $product->refillProduct($productId,$cleanerInfo['idLavador']);
  echo json_encode(array("Status"=>"OK"));
} catch(cleanerHasNoProductsException $e)
{
  echo json_encode(array("Status"=>"ERROR user not found"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (cleanerNotFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>