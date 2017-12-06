<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseProduct extends DataBase{
  
  const QUERY_READ_PRODUCTS = "SELECT Producto.idProducto, Lavador_Tiene_Producto.Cantidad, Producto.Producto, Producto.Descripcion FROM Lavador
    LEFT JOIN Lavador_Tiene_Producto ON
    Lavador.idLavador = Lavador_Tiene_Producto.idLavador
    LEFT JOIN Producto ON
    Lavador_Tiene_Producto.idProducto = Producto.idProducto
    WHERE Lavador.idLavador = '%s'
		;";
	const QUERY_UPDATE_PRODUCT = "UPDATE Lavador_Tiene_Producto SET Cantidad = '%s' WHERE idLavador = '%s' AND idProducto = '%s'
		;";
		
  
  public function readProductsForCleaner($cleanerId)
	{
		$query = sprintf(DataBaseProduct::QUERY_READ_PRODUCTS,$cleanerId);
		$result = $this->ejecutarQuery($query);
		
		if($result->num_rows === 0)
			throw new cleanerHasNoProductsException("No Products Found");
		
		return $result;
	}
  
  public function refillProductsForCleaner($productId,$cleanerId)
	{
		$query = sprintf(DataBaseProduct::QUERY_UPDATE_PRODUCT,"100", $cleanerId, $productId);
		$this->ejecutarQuery($query);
	}
}
	?>