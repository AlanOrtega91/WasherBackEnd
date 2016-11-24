<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
  die(json_encode(array("Satus"=>"ERROR missing values")));

try{
  $token = SafeString::safe($_POST['token']);
  $cleaner = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $product  = new Product();
  $products = $product->getProducts($cleanerInfo['idLavador']);
  echo json_encode(array("Status"=>"OK","Products"=>$products));
} catch(cleanerHasNoProductsException $e)
{
  echo json_encode(array("Status"=>"ERROR not enought products"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (cleanerNotFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
}
?>