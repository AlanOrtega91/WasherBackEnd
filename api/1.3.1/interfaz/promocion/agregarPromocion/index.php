<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Promocion.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['id']) || !isset($_POST['codigo']) 
    ||  !isset($_POST['latitud']) ||  !isset($_POST['longitud']))
{
  die(json_encode(array("Status"=>"ERROR missing values")));
}

try{
    $codigo = SafeString::safe($_POST['codigo']);
    $id = SafeString::safe($_POST['id']);
    $latitud = SafeString::safe($_POST['latitud']);
    $longitud = SafeString::safe($_POST['longitud']);
    
    $promocion = new Promocion();
  $promocion->agregarPromocion($codigo, $id, $latitud, $longitud);
  
  echo json_encode(array(
      "estado"=>"ok",
  )); 
  
} catch(codigoNoExiste $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"codigoNoExiste",
        "explicacion"=>$e->getMessage()
    ));
} catch(usuarioYaUsoCodigoDeInvitado $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"codigoUsado",
        "explicacion"=>$e->getMessage()
    ));
} catch(fechaDePromocionExpirada $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"codigoExpirado",
        "explicacion"=>$e->getMessage()
    ));
} catch(ubicacionDePromocionInvalida $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"ubicacion",
        "explicacion"=>$e->getMessage()
    ));
} catch(elCodigoYaFueUtilizado $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"codigoUsado",
        "explicacion"=>$e->getMessage()
    ));
} catch(errorWithDatabaseException $e)
{
    echo json_encode(array(
        "estado"=>"error",
        "clave"=>"db",
        "explicacion"=>$e->getMessage()
    ));
}
?>