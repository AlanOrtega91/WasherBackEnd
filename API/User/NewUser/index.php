<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/UsuariosClasses/User.php";

if (!isset($_POST['name']) || !isset($_POST['lastName']) || !isset($_POST['mail']) ||
    !isset($_POST['password']) || !isset($_POST['cel']))
  die(json_encode(array("Satus"=>"ERROR missing values")));
  


try{
  $name = SafeString::safe($_POST['name']);
  $lastName = SafeString::safe($_POST['lastName']);
  $mail = SafeString::safe($_POST['mail']);
  $password = SafeString::safe($_POST['password']);
  $cel = SafeString::safe($_POST['cel']);
  $image_name = "profile_image.jpg";
  $user  = new User();
  $userInfo = $user->addUser($name, $lastName, $mail, $password,$cel);
  if(isset($_POST['encoded_string']))
  {
    uploadImage($userInfo['idCliente']);
    $user->saveImage($userInfo['idCliente'], $image_name);
  }
  $userInfo = $user->sendLogIn($mail,$password);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo));
} catch(errorWithDatabaseException $e)
{
  echo $e->getMessage();
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