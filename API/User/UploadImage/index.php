<?php
header('Content-type: bitmap; charset=utf-8');
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
if (!isset($_POST['userId']) || !isset($_POST['encoded_string']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$encoded_string = $_POST['encoded_string'];
$image_name = "profile_image.jpg";
$userId = $_POST['userId'];
$decoded_string = base64_decode($encoded_string);
$directory = dirname(__FILE__).'/../../../images/users/'.$userId;
if(!is_dir($directory)) {
  mkdir($directory);
}
$path = dirname(__FILE__).'/../../../images/users/'.$userId.'/'.$image_name;

file_put_contents($path,$decoded_string);

try{
  $user  = new User();
  $user->saveImage($userId, $image_name);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>