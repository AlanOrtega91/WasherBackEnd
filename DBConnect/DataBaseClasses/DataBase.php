<?php
abstract class DataBase {
	
 	const DB_LINK = '127.0.0.1';
 	const DB_LOGIN = 'washerDBus4r';
 	const DB_PASSWORD ='lk_je9023U23daerD';
 	const DB_NAME = 'washer';
 	
//  	const DB_LINK = '127.0.0.1';
//  	const DB_LOGIN = 'root';
//  	const DB_PASSWORD ='';
//  	const DB_NAME = 'washer';
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