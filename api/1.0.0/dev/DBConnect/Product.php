<?php
require_once dirname(__FILE__)."/DataBaseClasses/DataBaseProduct.php";
class Product {
  
  private $dataBase;

	public function __construct()
	{
    $this->dataBase = new DataBaseProduct();
	}
  
  public function getProducts($cleanerId)
	{
		$products = $this->dataBase->readProductsForCleaner($cleanerId);
		$productsList = array();
		while($product = $products->fetch_assoc())
			array_push($productsList,$product);
      
		return $productsList;
	}
	
	public function refillProduct($productId,$cleanerId)
	{
		$this->dataBase->refillProductsForCleaner($productId,$cleanerId);
	}
}