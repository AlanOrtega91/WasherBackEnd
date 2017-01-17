<?php
class PushNotification {
	
	// (Android)API access key from Google API's Console.
	private static $API_ACCESS_KEY = 'AAAAhQZCWH8:APA91bEyp1ThX_XnUkKAFNgLxDYfhISo99PSTFTuyTd_8gjksbrL_Olw3Z7c6ypd5sRHajlbFpw552ponntqd9EqZ5qoPhzoESBYPpj-Culp_yDOi8qpJRBlfLGAKOIB4F4SVZhatKLd06t4vJR3kax2XtkFaKdM2w';
	// (iOS) Private key's passphrase.
	private static $passphrase = 'pene';
	// (Windows Phone 8) The name of our push channel.
	private static $channelName = "joashp";
	
	private static $production = false;
	
	public static function sendNotification($deviceId, $message, $deviceType, $userType) {
		switch ($deviceType){
			case "android":
				self::sendNotificationAndroid($deviceId,$message);
				break;
			case "ios":
				self::sendNotificationiOS($deviceId,$message);
				break;
		}
	}
	
	// Sends Push notification for iOS users
	private static function sendNotificationiOS($token, $message) {
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fieldsNotification = array (
				'notification' => array (
						"title" => "Washer",
						"body" => $message ['message'],
				),
				"data" => $message,
 				'priority' => 'high',
				'to' => $token
		);
		$fieldsMessage = array(
				"data" => $message,
				'priority' => 'high',
				'to' => $token
		);
		$headers = array (
				'Authorization:key = '. self::$API_ACCESS_KEY,
				'Content-Type: application/json'
		);
		
		self::useCurl($url, $headers, json_encode($fieldsNotification));
		self::useCurl($url, $headers, json_encode($fieldsMessage));
	}
	
	private static function sendNotificationAndroid($token, $message) {
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fieldsMessage = array(
				"data" => $message,
				'priority' => 'high',
				'registration_ids' => array($token)
		);
		$headers = array (
				'Authorization:key = '. self::$API_ACCESS_KEY,
				'Content-Type: application/json' 
		);
		
		self::useCurl($url, $headers, json_encode($fieldsMessage));
	}
	
	
	// Curl
	private static function useCurl($url, $headers, $fields = null) {
		// Open connection
		$ch = curl_init();
		if ($url) {
			// Set the url, number of POST vars, POST data
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
			// Disabling SSL Certificate support temporarly
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			if ($fields) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
			}
	
			// Execute post
			$result = curl_exec($ch);
			if ($result === FALSE) {
				// Close connection
				curl_close($ch);
				throw new errorSendingNotification ();
			}
	
			// Close connection
			curl_close($ch);
			
			return $result;
		}
	}
}
class errorSendingNotification extends Exception {
}
?>