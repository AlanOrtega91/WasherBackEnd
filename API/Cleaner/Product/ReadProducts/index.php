<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
if (!isset($_POST['cleanerId']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

$cleanerId = SafeString::safe($_POST['cleanerId']);
try{
  $product  = new Product();
  $products = $product->getProducts($cleanerId);
  echo json_encode(array("Status"=>"OK","Products"=>$products));
} catch(cleanerHasNoProductsException $e)
{
  echo json_encode(array("Status"=>"ERROR not enought products"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>