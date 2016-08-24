<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Cleaner.php";

if (!isset($_GET['idLavador']) || !isset($_GET['productId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $idCleaner = SafeString::safe($_GET['idLavador']);
  $productId = SafeString::safe($_GET['productId']);
  $product  = new Product();
  $product->refillProduct($productId,$idCleaner);
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