<?php
require_once dirname(__FILE__)."/DataBase.php";
class PromocionDB extends DataBase{
  
  const CREAR_PROMOCION = "INSERT INTO Promocion (codigo, tipo, cantidad, 
                latitudInicio, longitudInicio, latitudFinal, longitudFinal,
                fechaExpiracion, nombre, descripcion)
                VALUES('%s', '%s', %s,
                %s, %s, %s, %s,
                %s, '%s', '%s') !-- esta ultima linea es la que cambia si son null o no";
  
  CONST USUARIO_TIENE_CODIGO_DE_INVITADO = "SELECT * FROM Cliente 
                LEFT JOIN Abono
                On Cliente.idCliente = Abono.idCliente 
                WHERE Cliente.idCliente = %s 
                AND codigoUsuario IS NOT NULL";

  const USUARIO_ESTA_USANDO_SU_CODIGO = "SELECT * FROM Cliente WHERE idCliente = %s AND codigo = '%s'";
  
  const EXISTE_CODIGO_USUARIO = "SELECT * FROM Cliente 
                WHERE codigo = '%s'";
  const EXISTE_CODIGO_GENERADO = "SELECT * FROM Promocion 
                WHERE codigo = '%s' AND unico = FALSE";
  
  const AGREGAR_ABONO_INVITACION = "INSERT INTO Abono (codigoUsuario, idCliente, fecha, usado)
                VALUES('%s', %s, NOW(), true)";
  
  const AGREGAR_ABONO_PROMOCION = "INSERT INTO Abono (codigoPromocion, idCliente, fecha, usado)
                VALUES('%s', %s, NOW(), %s)";
  
  const AGREGAR_CREDITOS_A_USUARIO_INVITADO = "UPDATE Cliente SET credito = credito + %s 
                WHERE idCliente = %s";
  
  const AGREGAR_CREDITOS_A_USUARIO_QUE_INVITO = "UPDATE Cliente SET credito = credito + %s 
                WHERE codigo = '%s'";
  
  const LEER_PROMOCION = "SELECT * FROM Promocion WHERE codigo = '%s'";
  
  const USUARIO_TIENE_CODIGO = "SELECT * FROM Abono WHERE codigoPromocion = '%s' AND idCliente = %s";
  
  const PROMOCIONES_PARA_USUARIO = "SELECT * FROM Abono 
  				LEFT JOIN Promocion 
  				ON Abono.codigoPromocion = Promocion.codigo
  				WHERE idCliente = %s
  				AND usado = FALSE ";
  
  const USUARIO_TIENE_ABONO = "SELECT Abono.id AS id, Promocion.cantidad AS cantidad, 
  				Abono.numeroDeLavadasActuales AS numeroDeLavadasActuales,
  				Abono.codigoPromocion AS codigoPromocion, Abono.codigoUsuario AS codigoUsuario FROM Abono 
                LEFT JOIN Promocion 
                ON Abono.codigoPromocion = Promocion.codigo 
                WHERE idCliente = %s 
                AND 
                tipo = '%s' 
                AND usado = FALSE ";
  const MARCAR_DESCUENTO_COMO_USADO = "UPDATE Abono SET usado = TRUE WHERE id = %s";
  
  const DESCONTAR_CREDITO = "UPDATE Cliente SET credito = credito - %s
                WHERE idCliente = %s";

  const LEE_PROMOCIONES_POR_NUMERO = "SELECT * FROM Abono 
                LEFT JOIN Promocion 
                ON Abono.codigoPromocion = Promocion.codigo 
                WHERE idCliente = %s 
                AND 
                tipo = '\#' 
                AND usado = FALSE ";
  const AGREGAR_PROMOCION_UNICA = "INSERT INTO Promocion (codigo, tipo, cantidad, 
                nombre, descripcion, unico)
                VALUES('%s', '%s', 100,
                'Promocion regalo', 'Regalo por haber cumplido chingos', TRUE)";

  const AGREGAR_ABONO_POR_NUMERO_DE_LAVADAS = "INSERT INTO Abono (codigoPromocion, idCliente, fecha)
                VALUES(CONCAT('%s','U'), %s, NOW())";
  const ACTUALIZAR_LAVADAS_ACTUALES = "UPDATE Abono SET numeroDeLavadasActuales = %s
                WHERE id = %s";
  const MARCAR_PROMOCION_COMO_USADA = "UPDATE Abono SET usado = TRUE
                WHERE id = %s";

  const AGREGAR_REFERENCIA_DESCUENTO = "INSERT INTO Abono_Utilizado (idAbono, idServicioPedido)
  				VALUES(%s, %s)";
  const AGREGAR_REFERENCIA_CREDITO = "INSERT INTO Credito_Utilizado (idCliente, idServicioPedido, cantidad)
  				VALUES(%s, %s, %s)";
  
  public function crearPromocion($codigo, $tipo, $cantidad,
      $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal,
      $fechaExpiracion, $nombre, $descripcion)
	{
      $QUERY_INSERTS = "INSERT INTO Promocion (codigo, tipo, cantidad, 
                latitudInicio, longitudInicio, latitudFinal, longitudFinal,
                fechaExpiracion, nombre, descripcion) ";
      
      $fechaFormato = $this->formatoDePosibleNullEnTexto($fechaExpiracion);
      $nombreFormato = $this->formatoDePosibleNullEnTexto($nombre);
      $descripcionFormato = $this->formatoDePosibleNullEnTexto($descripcion);
      $QUERY_VALUES = "VALUES('%s', '%s', %s,
                %s, %s, %s, %s,
                ". $fechaFormato .",". $nombreFormato .",". $descripcionFormato .")";
      
      $query = sprintf ( $QUERY_INSERTS.$QUERY_VALUES, $codigo, $tipo, $cantidad,
          $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal,
          $fechaExpiracion, $nombre, $descripcion);
      $this->ejecutarQuery($query);
	}
	
	function existeCodigoDeUsuario($codigo)
	{
	    $query = sprintf ( self::EXISTE_CODIGO_USUARIO, $codigo);
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}

	function existeCodigoGenerado($codigo)
	{
	    $query = sprintf ( self::EXISTE_CODIGO_GENERADO, $codigo);
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}
	
	
	function usuarioTieneCodigoConNumero($id)
	{
	    $query = sprintf ( self::USUARIO_TIENE_CODIGO_DE_INVITADO, $id);
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}

	function usuarioUsandoSuCodigo($codigo, $id)
	{
		$query = sprintf ( self::USUARIO_ESTA_USANDO_SU_CODIGO, $id, $codigo);
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}
	
	function agregarAbonoInvitacion($codigo, $id)
	{
	    $query = sprintf ( self::AGREGAR_ABONO_INVITACION, $codigo, $id);
	    $this->ejecutarQuery($query);
	}
	
	function agregarAbonoDePromocion($codigo, $id, $usado)
	{
	    $query = sprintf ( self::AGREGAR_ABONO_PROMOCION, $codigo, $id, $usado);
	    $this->ejecutarQuery($query);
	}
	
	function agregarCreditosAUsuarioInvitado($id, $cantidad)
	{
	    $query = sprintf ( self::AGREGAR_CREDITOS_A_USUARIO_INVITADO, $cantidad, $id);
	    $this->ejecutarQuery($query);
	}
	
	function agregarCreditosAUsuarioQueInvito($codigo, $cantidad)
	{
	    $query = sprintf ( self::AGREGAR_CREDITOS_A_USUARIO_QUE_INVITO, $cantidad, $codigo);
	    $this->ejecutarQuery($query);
	}
	
	
	function leerPromocion($codigo)
	{
	    $query = sprintf(self::LEER_PROMOCION, $codigo);
	    $resultado = $this->ejecutarQuery($query);
	    return $resultado->fetch_assoc();
	}
	
	function usuarioYaUsoElCodigo($codigo, $id) 
	{
	    $query = sprintf ( self::USUARIO_TIENE_CODIGO, $codigo, $id);
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}
	
	function leerPromocionesParaUsuario($id)
	{
	    $query = sprintf ( self::PROMOCIONES_PARA_USUARIO, $id);
	    $resultado = $this->ejecutarQuery($query);
	    return $resultado;
	} 
	
	function usuarioTieneDescuento($id)
	{
	    $query = sprintf ( self::USUARIO_TIENE_ABONO, $id, "%");
	    $resultado = $this->ejecutarQuery($query);
	    return $this->resultadoTieneValores($resultado);
	}
	
	function leeUnDescuentoDeUsuario($id)
	{
	    $query = sprintf ( self::USUARIO_TIENE_ABONO, $id, "%");
	    $resultado = $this->ejecutarQuery($query);
	    return $resultado->fetch_assoc();
	}
	
	function marcarDescuentoComoUsado($id)
	{
	    $query = sprintf ( self::MARCAR_DESCUENTO_COMO_USADO, $id);
	    $this->ejecutarQuery($query);
	}
	
	function descontarCredito($id, $creditoAUtilizar)
	{
	    $query = sprintf ( self::DESCONTAR_CREDITO, $creditoAUtilizar, $id);
	    $this->ejecutarQuery($query);
	}

	function leerUnaPromocionPorNumero($id)
	{
		$query = sprintf ( self::USUARIO_TIENE_ABONO, $id, "#");
	    $resultado = $this->ejecutarQuery($query);
	    return $resultado->fetch_assoc();
	}

	function creaPromocionUnica($nuevoCodigo)
	{
		$query = sprintf ( self::AGREGAR_PROMOCION_UNICA, $nuevoCodigo,'%');
	    $this->ejecutarQuery($query);
	}

	function agregarAbonoPorLavadas($id, $codigo)
	{
		$query = sprintf ( self::AGREGAR_ABONO_POR_NUMERO_DE_LAVADAS, $codigo, $id);
	    $this->ejecutarQuery($query);
	}

	function actualizarLavadasActuales($id, $numeroDeLavadas)
	{
		$query = sprintf ( self::ACTUALIZAR_LAVADAS_ACTUALES, $numeroDeLavadas, $id);
	    $this->ejecutarQuery($query);
	}

	function agregarReferenciaDeDescuentoUsado($idServicio, $idAbono)
	{
		$query = sprintf ( self::AGREGAR_REFERENCIA_DESCUENTO, $idAbono, $idServicio);
	    $this->ejecutarQuery($query);
	}

	function agregarReferenciaDeCreditoUsado($idServicio, $idCliente, $cantidad)
	{
		$query = sprintf ( self::AGREGAR_REFERENCIA_CREDITO, $idCliente, $idServicio, $cantidad);
	    $this->ejecutarQuery($query);
	}

	function formatoDePosibleNullEnTexto($valor)
	{
	    $formato = "";
	    if (strcmp($valor,"NULL") == 0) {
	        $formato = "%s";
	    } else {
	        $formato = "'%s'";
	    }
	    
	    return $formato;
	}
}
?>
