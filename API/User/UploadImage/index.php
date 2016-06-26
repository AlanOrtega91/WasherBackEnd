<?php
header('Content-type: bitmap; charset=utf-8');
include dirname(__FILE__)."/../../../DBConnect/SafeString.php";
include dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
if (!isset($_POST['userId']) || !isset($_POST['encoded_string']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  
$encoded_string = $_POST['encoded_string'];
$image_name = "Profile_image.jpg";
$userId = $_POST['userId'];
$decoded_string = base64_decode($encoded_string);
$directory = dirname(__FILE__).'/../../../images/users/'.$cleanerId;
if(!is_dir($directory)) {
  mkdir($directory);
}
$path = dirname(__FILE__).'/../../../images/users/'.$cleanerId.'/'.$image_name;

$file = fopen($path,'wb');

$is_written = fwrite($file,$decoded_string);
fclose($file);
try{
  if($is_written > 0){
    $user  = new User();
    $user->saveImage($userId, $image_name);
    echo json_encode(array("Status"=>"OK"));
  }
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}
?>