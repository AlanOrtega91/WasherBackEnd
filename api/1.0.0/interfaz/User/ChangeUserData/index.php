<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['newName']) || !isset($_POST['newLastName']) ||
    !isset($_POST['newEmail']) || !isset($_POST['token']) || !isset($_POST['newPhone']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $newName = SafeString::safe($_POST['newName']);
  $newLastName = SafeString::safe($_POST['newLastName']);
  $newEmail = SafeString::safe($_POST['newEmail']);
  $token = SafeString::safe($_POST['token']);
  $newPhone =  SafeString::safe($_POST['newPhone']);
  if(!isset($_POST['newBillingName']) || !isset($_POST['newRFC']) ||!isset($_POST['newBillingAddress'])){
    $newBillingName = null;
    $newRFC = null;
    $newBillingAddress = null;
  } else {
    $newBillingName = SafeString::safe($_POST['newBillingName']);
    $newRFC = SafeString::safe($_POST['newRFC']);
    $newBillingAddress = SafeString::safe($_POST['newBillingAddress']);
  }
  $image_name = "profile_image.jpg";

  $user  = new User();
  $infoUser = $user->userHasToken($token);
  $user->changeData($infoUser['idCliente'],$newName, $newLastName,$newPhone, $newEmail, $newBillingName, $newRFC, $newBillingAddress);
  
  if(isset($_POST['encoded_string']))
  {
    uploadImage($infoUser['idCliente']);
    $user->saveImage($infoUser['idCliente'], $image_name);
  }
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} catch (noSessionFoundException $e) {
	echo json_encode(array("Status" => "SESSION ERROR"));
}

function uploadImage($idClient){
  $encoded_string = $_POST['encoded_string'];
  $encoded_string = str_replace('data:image/jpg;base64,', '', $encoded_string);
  $encoded_string = str_replace(' ', '+', $encoded_string);
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