<?php
/*
Metodo POST
api/1.3.0/interfaz/promocion/crearPromocion/

Parametros minimos
Tipo ----- "tipo"
    Tipo de promocion que es, los valores deben ser '%','#','$'

Cantidad ---- "cantidad"
    Cantidad de la promocion que se aplicara, los valores deben ir de 0 al 100

Codigo ----- "codigo"
    El codigo bajo el cual el usuario redimira la promocion, debera comenzar con una letra seguido de hasta 50 caracteres (debe ser unico)

Parametros extra (en caso de no utilizarlos mantener el post sin estos parametros)
Latitud Inicio ----- "latitudInicio"
    Sirve para restringuir la promocion a un area.
Longitud Inicio ----- "longitudInicio"
    Sirve para restringuir la promocion a un area.
Latitud Final ----- "latitudFinal"
    Sirve para restringuir la promocion a un area.
Longitud Final ----- "longitudFinal"
    Sirve para restringuir la promocion a un area.
Fecha de Expiracion ----- "fechaExpiracion"
    Sirve para restringuir la promocion a una fecha limite, el formato debera ser yyyy-mm-ss hh:MM:ss.
Nombre ----- "nombre"
    Nombre de la promocion. Este nombre se le muestra al usuario
Descripcion ----- "descripcion"
    Descripcion de la promocion


*/

require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Promocion.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['tipo']) || !isset($_POST['cantidad']) ||  !isset($_POST['codigo']))
{
  die(json_encode(array("Status"=>"ERROR missing values")));
}

try{
    $codigo = SafeString::safe($_POST['codigo']);
    $tipo = SafeString::safe($_POST['tipo']);
    $cantidad = SafeString::safe($_POST['cantidad']);
    
    $latitudInicio = "NULL";
    $longitudInicio = "NULL";
    $latitudFinal = "NULL";
    $longitudFinal = "NULL";
    if (isset($_POST['latitudInicio']) && isset($_POST['longitudInicio']) && isset($_POST['latitudFinal']) && isset($_POST['longitudFinal'])) 
    {
        $latitudInicio = SafeString::safe($_POST['latitudInicio']);
        $longitudInicio = SafeString::safe($_POST['longitudInicio']);
        $latitudFinal = SafeString::safe($_POST['latitudFinal']);
        $longitudFinal = SafeString::safe($_POST['longitudFinal']);
    }
    $fechaExpiracion = "NULL";
    if (isset($_POST['fechaExpiracion']))
    {
        $fechaExpiracion = SafeString::safe($_POST['fechaExpiracion']);
    }
    $nombre = "NULL";
    if (isset($_POST['nombre']))
    {
        $nombre = SafeString::safe($_POST['nombre']);
    }
    $descripcion = "NULL";
    if (isset($_POST['descripcion']))
    {
        $descripcion = SafeString::safe($_POST['descripcion']);
    }
    $promocion = new Promocion();
  $promocion->crearPromocion($codigo, $tipo, $cantidad, 
      $latitudInicio, $longitudInicio, $latitudFinal, $longitudFinal, 
      $fechaExpiracion, $nombre, $descripcion);
  
  echo json_encode(array(
      "estado"=>"ok",
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

