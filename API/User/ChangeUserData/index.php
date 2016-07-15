<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";
if (!isset($_POST['newName']) || !isset($_POST['newLastName']) ||
    !isset($_POST['newMail']) || !isset($_POST['idClient']) || !isset($_POST['newCel']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
$newName = SafeString::safe($_POST['newName']);
$newLastName = SafeString::safe($_POST['newLastName']);
$newMail = SafeString::safe($_POST['newMail']);
$idClient = SafeString::safe($_POST['idClient']);
$newCel =  SafeString::safe($_POST['newCel']);
if(!isset($_POST['newBillingName']) || !isset($_POST['newRFC']) ||!isset($_POST['newBillingAddress'])){
  $newBillingName = "";
  $newRFC = "";
  $newBillingAddress = "";
} else {
  $newBillingName = SafeString::safe($_POST['newBillingName']);
  $newRFC = SafeString::safe($_POST['newRFC']);
  $newBillingAddress = SafeString::safe($_POST['newBillingAddress']);
}
$image_name = "profile_image.jpg";
try{
  $user  = new User();
  $user->changeData($idClient,$newName, $newLastName,$newCel, $newMail, $newBillingName, $newRFC, $newBillingAddress);
  
  if(isset($_POST['encoded_string']))
  {
    uploadImage($idClient);
    $user->saveImage($idClient, $image_name);
  }
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
}

function uploadImage($idClient){
  $encoded_string = $_POST['encoded_string'];
  $image_name = "profile_image.jpg";
  $decoded_string = base64_decode($encoded_string);
  $directory = dirname(__FILE__).'/../../../images/users/'.$idClient;
  $oldmask = umask(0);
  if(!is_dir($directory)) {
    mkdir($directory, 0777);
  }
  $path = dirname(__FILE__).'/../../../images/users/'.$idClient.'/'.$image_name;
  
  file_put_contents($path,$decoded_string);
  umask($oldmask);
}
?>