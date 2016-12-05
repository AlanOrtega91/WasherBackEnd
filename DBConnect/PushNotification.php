<?php
class PushNotification {
	
	// (Android)API access key from Google API's Console.
	private static $API_ACCESS_KEY = 'AAAAhQZCWH8:APA91bEyp1ThX_XnUkKAFNgLxDYfhISo99PSTFTuyTd_8gjksbrL_Olw3Z7c6ypd5sRHajlbFpw552ponntqd9EqZ5qoPhzoESBYPpj-Culp_yDOi8qpJRBlfLGAKOIB4F4SVZhatKLd06t4vJR3kax2XtkFaKdM2w';
	// (iOS) Private key's passphrase.
	private static $passphrase = 'joashp';
	// (Windows Phone 8) The name of our push channel.
	private static $channelName = "joashp";
	
	private static $production = false;
	
	public static function sendNotification($deviceId, $message, $deviceType) {
		switch ($deviceType){
			case "android":
				self::sendNotificationAndroid($deviceId,$message);
				break;
			case "ios":
				break;
		}
	}
	
	// Sends Push notification for iOS users
	private function sendNotificationiOS($message, $devicetoken) {
		$ctx = stream_context_create();
		if (self::$production) {
			$gateway = 'gateway.push.apple.com:2195';
		} else {
			$gateway = 'gateway.sandbox.push.apple.com:2195';
		}
		// ck.pem is your certificate file
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client(
				$gateway, $err,
				$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp) {
			throw new errorSendingNotification ("Failed to connect: $err $errstr" . PHP_EOL);
		}
		// Create the payload body
		$body['aps'] = array(
				'alert' => array(
						'title' => 'Washer',
						'body' => $message ['message'],
			 ),
				"data" => $message,
				'sound' => 'default'
		);
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));

		// Close the connection to the server
		fclose($fp);
		if (!$result)
			throw new errorSendingNotification ('Message not delivered' . PHP_EOL);
		else
			return 'Message successfully delivered' . PHP_EOL;
	}
	
	private static function sendNotificationAndroid($token, $message) {
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array (
				'notification' => array (
						"title" => "Washer",
						"body" => $message ['message'],
						"icon" => "appicon",
						"sound" => "default" 
				),
				"data" => $message,
				'priority' => 'high',
				'registration_ids' => array($token) 
		);
		$headers = array (
				'Authorization:key = '. self::$API_ACCESS_KEY,
				'Content-Type: application/json' 
		);
		
		return self::useCurl($url, $headers, json_encode($fields));
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