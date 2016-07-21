<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
if (!isset($_POST['cleanerId']) || !isset($_POST['productId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $cleanerId = SafeString::safe($_POST['cleanerId']);
  $productId = SafeString::safe($_POST['productId']);
  $product  = new Product();
  $product->refillProduct($productId,$cleanerId);
  echo json_encode(array("Status"=>"OK"));
} catch(cleanerHasNoProductsException $e)
{
  echo json_encode(array("Status"=>"ERROR user not found"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>