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
}

?>