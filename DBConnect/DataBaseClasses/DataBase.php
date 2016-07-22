<?php
abstract class DataBase {
	
// 	const DB_LINK = 'imaniozone.ipagemysql.com';
// 	const DB_LOGIN = 'gilton';
// 	const DB_PASSWORD ='gilton';
// 	const DB_NAME = 'gilton';
		
	const DB_LINK = '127.0.0.1';
	const DB_LOGIN = 'root';
	const DB_PASSWORD ='';
	const DB_NAME = 'gilton';	
}

class carsNotFoundException extends Exception{
	}
class errorWithDatabaseException extends Exception{
	}
class sessionNotFoundException extends Exception{
	}
class userNotFoundException extends Exception{
	}
class cleanerNotFoundException extends Exception{
	}
	class cleanerHasNoProductsException extends Exception{
	}
class serviceNotFoundException extends Exception{
	}
class serviceTakenException extends Exception {
	}
class insufficientProductException extends Exception {
}
class noSessionFoundException extends Exception {
}
?>