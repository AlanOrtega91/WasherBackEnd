<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Promocion.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['id']))
{
  die(json_encode(array("Status"=>"ERROR missing values")));
}

try{
    $id = SafeString::safe($_POST['id']);
    $promocion = new Promocion();
    $promociones = $promocion->leerPromociones($id);
  
  echo json_encode(array(
      "estado"=>"ok",
      "promociones" => $promociones
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