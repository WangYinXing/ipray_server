<?php


class Qbhelper {

	function __construct() {
		$this->latestErr = "";
	}

	/*--------------------------------------------------------------------------------------------------------
		Authorize to QB and get Token...
	_________________________________________________________________________________________________________*/
	public function generateSession() {
		// Generate signature
		$nonce = rand();
		$timestamp = time(); // time() method must return current timestamp in UTC but seems like hi is return timestamp in current time zone
		$signature_string = "application_id=" . QB_APP_ID . "&auth_key=" . QB_AUTH_KEY . "&nonce=" . $nonce . "&timestamp=" . $timestamp;

		$signature = hash_hmac('sha1', $signature_string , QB_AUTH_SECRET);

		//echo $signature;
		//echo $timestamp;

		// Build post body
		$post_body = http_build_query( array(
			'application_id' => QB_APP_ID,
			'auth_key' => QB_AUTH_KEY,
			'timestamp' => $timestamp,
			'nonce' => $nonce,
			'signature' => $signature,
		));

		// Configure cURL
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, QB_API_ENDPOINT . '/' . QB_PATH_AUTH); // Full path is - https://api.quickblox.com/auth.json
		curl_setopt($curl, CURLOPT_POST, true); // Use POST
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body); // Setup post body
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // Receive server response
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		// Execute request and read response
		$response = curl_exec($curl);

		$token = null;

		try {
			$this->authInfo = json_decode($response);
			$token = $this->authInfo->session->token;
		}
		catch (Exception $e) {
			curl_close($curl);
			return null;
		}

		// Close connection
		curl_close($curl);

		return $token;
	}

	/*--------------------------------------------------------------------------------------------------------
		Create new user...
	_________________________________________________________________________________________________________*/
	public function signupUser($token, $login, $email, $password) {
		$request = json_encode(array(
			'user' => array(
		 		'login' => $login,
		  		'email' => $email,
		  		'password' => $password,
		  	)
		));
		 
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, QB_API_ENDPOINT . '/' . QB_PATH_USER); // Full path is - https://api.quickblox.com/auth.json
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  'Content-Type: application/json',
		  'QuickBlox-REST-API-Version: 0.1.0',
		  'QB-Token: ' . $token
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 
		$response = curl_exec($ch);

		$user = null;

		/*

		*/
		ob_start();
		try {
			$resp = json_decode($response);

			$error = $resp->errors;

			if ($error) {
				$this->latestErr = json_encode($error);
				return null;
			}


			$user = json_decode($response)->user;
		}
		catch (Exception $e) {
			curl_close($ch);
			return null;
		}
		ob_end_clean();

		curl_close($ch);

		return $user;
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign in ...
	_________________________________________________________________________________________________________*/
	public function signinUser($qbToken, $email, $password) {
		$request = json_encode(array(
			//'login' => $login,
	  		'email' => $email,
	  		'password' => $password,
		));
		 
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, QB_API_ENDPOINT . '/' . QB_PATH_LOGIN); // Full path is - https://api.quickblox.com/auth.json
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		  'Content-Type: application/json',
		  'QuickBlox-REST-API-Version: 0.1.0',
		  'QB-Token: ' . $qbToken
		));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		 
		$response = curl_exec($ch);

		$user = null;
		
		/*

		*/
		ob_start();


		try {
			$user = json_decode($response)->user;
		}
		catch (Exception $e) {
			$this->latestErr = json_decode($response);
			curl_close($ch);
			return null;
		}
		ob_end_clean();

		curl_close($ch);

		$this->latestErr = "";
		return $user;
	}
	/*--------------------------------------------------------------------------------------------------------
		Sign out ...
	_________________________________________________________________________________________________________*/
	public function signoutUser($email) {

	}

	/*--------------------------------------------------------------------------------------------------------
		Send Push notification to specific user ...
	_________________________________________________________________________________________________________*/
	public function sendPN($deviceToken, $content) {
		// set time limit to zero in order to avoid timeout
		set_time_limit(0);
		 
		// charset header for output
		header('content-type: text/html; charset: utf-8');
		 
		// this is the pass phrase you defined when creating the key
		$passphrase = 'ipray';
		 
		// you can post a variable to this string or edit the message here
		 
		// tr_to_utf function needed to fix the Turkish characters
		//$message = tr_to_utf("blah blah blah...");
		 
		// load your device ids to an array
		$deviceIds = array(
			$deviceToken
		);
		 
		// this is where you can customize your notification
		//$payload = '{"aps":{"alert":"' . $message . '","sound":"default", "type": "ipray_invitation", "sender":"' . $sender . '", "receiver":"' . $receiver . '"}}';
		$payload = '{"aps":' . $content . '}';

		//$payload = '{"aps":{"alert":"blah blah blah...","sound":"default", "type": "ipray_invitation"}}';
		 
		$result = 'Start' . '<br />';

		ob_start();
		 
		////////////////////////////////////////////////////////////////////////////////
		// start to create connection
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'ipray_apn_dev.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		 
		echo count($deviceIds) . ' devices will receive notifications.<br />';

		$undelivered = 0;
		 
		foreach ($deviceIds as $item) {
		    // wait for some time
		    sleep(1);
		     
		    // Open a connection to the APNS server
		    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
		 
		    if (!$fp) {
		        exit("Failed to connect: $err $errstr" . '<br />');
		    } else {
		        echo 'Apple service is online. ' . '<br />';
		    }
		 
		    // Build the binary notification
		    $msg = chr(0) . pack('n', 32) . pack('H*', $item) . pack('n', strlen($payload)) . $payload;
		     
		    // Send it to the server
		    $result = fwrite($fp, $msg, strlen($msg));
		     
		    if (!$result) {
		        //echo 'Undelivered message count: ' . $item . '<br />';
		        $undelivered++;
		    } else {
		        echo 'Delivered message count: ' . $item . '<br />';
		    }
		 
		    if ($fp) {
		        fclose($fp);
		        echo 'The connection has been closed by the client' . '<br />';
		    }
		}
		 
		echo count($deviceIds) . ' devices have received notifications.<br />';

		ob_end_clean();
		 
		// set time limit back to a normal value
		set_time_limit(30);

		return $undelivered;
	}
/*
	// function for fixing Turkish characters
		function tr_to_utf($text) {
		    $text = trim($text);
		    $search = array('Ü', 'Þ', 'Ð', 'Ç', 'Ý', 'Ö', 'ü', 'þ', 'ð', 'ç', 'ý', 'ö');
		    $replace = array('Ãœ', 'Åž', '&#286;ž', 'Ã‡', 'Ä°', 'Ã–', 'Ã¼', 'ÅŸ', 'ÄŸ', 'Ã§', 'Ä±', 'Ã¶');
		    $new_text = str_replace($search, $replace, $text);
		    return $new_text;
		}
*/
}

?>