<?php
class PushNotification{
			public static function sendNotification($token, $message){
						$url = 'https://fcm.googleapis.com/fcm/send';
						$fields = array(
							'notification' => array(
																															"title" => "Vashen",
																															"body" => $message['message'],
																															"icon" => "default",
																															"sound" => "default"
																															),
							"data" => $message,
							'to' => $token
						);
						$headers = array(
									'Authorization:key = AIzaSyAKHM3MoMACjmeVK46TDg8-rTj1KoVjzWs',
									'Content-Type: application/json'
						);
						$ch = curl_init();
						curl_setopt($ch,CURLOPT_URL,$url);
						curl_setopt($ch,CURLOPT_POST,true);
						curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
						curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
						curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
						curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
						$result = curl_exec($ch);
						if($result === FALSE) {
			
									throw new errorSendingNotification();
						}
						curl_close($ch);
						return $result;
			}
}
class errorSendingNotification extends Exception{
	}
?>