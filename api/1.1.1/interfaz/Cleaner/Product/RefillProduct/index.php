<?php
require_once dirname(__FILE__)."/../../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Product.php";
require_once dirname(__FILE__)."/../../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['idLavador']) || !isset($_POST['productId']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
}


try{
  $idCleaner = SafeString::safe($_POST['idLavador']);
  $productId = SafeString::safe($_POST['productId']);
  $product  = new Product();
  $product->refillProduct($productId,$idCleaner);
  echo json_encode(array(
  		"estado"=>"ok"
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