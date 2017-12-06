<?php
require_once dirname(__FILE__)."/DataBaseClasses/PromocionDB.php";
class Promocion {
  
  private $dataBase;
  const CANTIDAD_POR_ABONAR = 100;

	public function __construct()
	{
	    $this->dataBase = new PromocionDB();
	}
  
	public function crearPromocion($codigo, $tipo, $cantidad,
	    $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal,
	    $fechaExpiracion, $nombre, $descripcion)
	{
	    $products = $this->dataBase->crearPromocion($codigo, $tipo, $cantidad,
	        $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal,
	        $fechaExpiracion, $nombre, $descripcion);
	}
	
	function agregarPromocion($codigo, $id, $latitud, $longitud)
	{
	    $tipo = substr($codigo, 0, 1);
	    if (is_numeric($tipo)) 
	    {
	        $this->agregaPromocionDeInvitado($codigo, $id);
	    } else 
	    {
	        $this->agregaPromocionGenerada($codigo, $id, $latitud, $longitud);
	    }
	}
	
	function agregaPromocionDeInvitado($codigo, $id)
	{
	    if (!$this->dataBase->existeCodigoDeUsuario($codigo)) 
	    {
	        throw new codigoNoExiste();
	    }
	    if ($this->dataBase->usuarioTieneCodigoConNumero($id))
	    {
	        throw new usuarioYaUsoCodigoDeInvitado();
	    } else 
	    {
	        $this->dataBase->agregarAbonoInvitacion($codigo, $id);
	        $this->dataBase->agregarCreditosAUsuarioInvitado($id, self::CANTIDAD_POR_ABONAR);
	        $this->dataBase->agregarCreditosAUsuarioQueInvito($codigo, self::CANTIDAD_POR_ABONAR);
	    }
	}
	
	function agregaPromocionGenerada($codigo, $id, $latitud, $longitud)
	{
		if (!$this->dataBase->existeCodigoGenerado($codigo)) 
	    {
	        throw new codigoNoExiste();
	    }
	    $promocion = $this->dataBase->leerPromocion($codigo);
	    $this->revisaFechaValida($promocion['fechaExpiracion']);
	    $this->revisaUbicacionValida($promocion['latitudInicio'], $promocion['longitudInicio'], 
	        $promocion['latitudFinal'], $promocion['longitudFinal'], 
	        $latitud, $longitud);
	    $this->revisaSiUsuarioYaLaUso($codigo, $id);
	    $usado = "FALSE";
	    if (strcmp($promocion['tipo'], '$') == 0) 
	    {
	        $usado = "TRUE";
	        $this->dataBase->agregarCreditosAUsuarioInvitado($id, $promocion['cantidad']);
	    }
	    $this->dataBase->agregarAbonoDePromocion($codigo, $id, $usado);
	}
	
	function revisaFechaValida($fecha)
	{
	    date_default_timezone_set ( 'America/Mexico_City' );
	    $fechaActual = date ( "Y-m-d H:i:s" );
	    if (!is_null($fecha)) 
	    {
	        if ($fechaActual > $fecha) 
	        {
	            throw new fechaDePromocionExpirada();
	        }
	    }
	}
	
	function revisaUbicacionValida($latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal, $latitudCliente, $longitudCliente)
	{
	    if (!is_null($latitudInicio) && !is_null($longitudInicio) && !is_null($latitudFinal) && !is_null($longitudFinal)) 
	    {
	        
	        $dentroDeLasLatitudes = ($latitudInicio < $latitudCliente && $latitudCliente < $latitudFinal)
	                               || ($latitudInicio > $latitudCliente && $latitudCliente > $latitudFinal);
	        
	        $dentroDeLasLongitudes = ($longitudInicio < $longitudCliente && $longitudCliente < $longitudFinal)
	                               || ($longitudInicio > $longitudCliente && $longitudCliente > $longitudFinal);
	        
	        if (!($dentroDeLasLatitudes && $dentroDeLasLongitudes)) 
	        {
	            throw new ubicacionDePromocionInvalida();
	        }
	    }
	}
	
	function revisaSiUsuarioYaLaUso($codigo, $id)
	{
	    if ($this->dataBase->usuarioYaUsoElCodigo($codigo, $id)) 
	    {
	        throw new elCodigoYaFueUtilizado();
	    }
	}
	
	function leerPromociones($id)
	{
	    $promociones = $this->dataBase->leerPromocionesParaUsuario($id);
	    for ($listaPromociones = array(); $fila = $promociones->fetch_assoc(); $listaPromociones[] = $fila);
	    return $listaPromociones;
	}
	
}

class codigoNoExiste extends Exception{}
class usuarioYaUsoCodigoDeInvitado extends Exception{}
class fechaDePromocionExpirada extends Exception{}
class ubicacionDePromocionInvalida extends Exception{}
class elCodigoYaFueUtilizado extends Exception{}
?>