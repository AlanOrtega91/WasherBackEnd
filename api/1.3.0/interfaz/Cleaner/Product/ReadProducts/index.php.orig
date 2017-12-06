<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['token']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}

try
{
  $token = SafeString::safe($_POST['token']);
  $cleaner = new Cleaner();
  $cleanerInfo = $cleaner->userHasToken($token);
  $product  = new Product();
  $products = $product->getProducts($cleanerInfo['idLavador']);
  echo json_encode(array(
  		"estado"=>"ok",
  		"productos"=>$products
  ));
} 
catch(cleanerHasNoProductsException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"productos",
			"explicacion"=>$e->getMessage()
	));
} 
catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
catch (cleanerNotFoundException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"lavador",
			"explicacion"=>$e->getMessage()
	));
}
?>