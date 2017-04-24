<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseProduct {
  
  const QUERY_READ_PRODUCTS = "SELECT Producto.idProducto, Lavador_Tiene_Producto.Cantidad, Producto.Producto, Producto.Descripcion FROM Lavador
    LEFT JOIN Lavador_Tiene_Producto ON
    Lavador.idLavador = Lavador_Tiene_Producto.idLavador
    LEFT JOIN Producto ON
    Lavador_Tiene_Producto.idProducto = Producto.idProducto
    WHERE Lavador.idLavador = '%s'
		;";
	const QUERY_UPDATE_PRODUCT = "UPDATE Lavador_Tiene_Producto SET Cantidad = '%s' WHERE idLavador = '%s' AND idProducto = '%s'
		;";
		
    
    var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
		$this->mysqli->set_charset("utf8");
  }
  
  public function readProductsForCleaner($cleanerId)
	{
		$query = sprintf(DataBaseProduct::QUERY_READ_PRODUCTS,$cleanerId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
		
		if($result->num_rows === 0)
			throw new cleanerHasNoProductsException("No Products Found");
		
		return $result;
	}
  
  public function refillProductsForCleaner($productId,$cleanerId)
	{
		$query = sprintf(DataBaseProduct::QUERY_UPDATE_PRODUCT,"100", $cleanerId, $productId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed');
	}
}
	?>