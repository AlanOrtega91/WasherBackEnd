<?php
include dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../../DBConnect/Product.php";
if (!isset($_GET['cleanerId']) || !isset($_GET['productId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$cleanerId = SafeString::safe($_GET['cleanerId']);
$productId = SafeString::safe($_GET['productId']);

try{
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