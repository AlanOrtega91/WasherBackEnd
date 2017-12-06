<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']) || !isset($_POST['newPassword']) || !isset($_POST['oldPassword']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
			
	)));
} 

try
{
  $email = SafeString::safe($_POST['email']);
  $newPassword = SafeString::safe($_POST['newPassword']);
  $oldPassword = SafeString::safe($_POST['oldPassword']);
  $user  = new User();
  $user->changePassword($email, $newPassword, $oldPassword);
  echo json_encode(array(
  		"estado"=>"ok"
  ));
} 
catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
catch(userNotFoundException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"usuario",
			"explicacion"=>$e->getMessage()
	));
} 
?>