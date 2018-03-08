<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";

require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqStreetArt\{
	Profile
};

/**
 * API for app sign in, Profile class
 *
 * POST requests are supported.
 *
 * @author Rochelle Lewis <rlewis37@cnm.edu>
 * @author Erin Scott <erinleeannscott@gmail.com>
 **/

/**
 * Prepare an empty reply.
 *
 * Here we create a new stdClass named $reply. A stdClass is basically an empty bucket that we can use to store things in.
 *
 * We will use this object named $reply to store the results of the call to our API. The status 200 line adds a state variable to $reply called status and initializes it with the integer 200 (success code). The proceeding line adds a state variable to $reply called data. This is where the result of the API call will be stored. We will also update $reply->message as we proceed through the API.
 **/

//check the session status. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {

	//grab the database connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

	//determine which HTTP method, store the result in $method
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	if($method === "POST") {

		//check xsrf token
		verifyXsrf();

		//grab request content, decode json into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//check for password (required field)
		if(empty($requestObject->profilePassword) === true) {
			throw (new \InvalidArgumentException("A password must be entered.", 401));
		} else {
			$profilePassword = $requestObject->profilePassword;
		}

		//check for email (required field)
		if(empty($requestObject->profileEmail) === true) {
			throw (new \InvalidArgumentException("An email address must be entered.", 401));
		} else {
			$profileEmail = filter_var($requestObject->profileEmail, FILTER_SANITIZE_EMAIL);
		}

		//grab the profile by email address
		$profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
		if(empty($profile) === true) {
			throw (new \RuntimeException("Invalid username Snacks", 401));
		}

		//hash the password provided by the user
		$hash = hash_pbkdf2("sha512", $profilePassword, $profile->getProfileSalt(), 262144);

		//check if the password hash matches what is in MySQL
		if($hash !== $profile->getProfileHash()) {
			throw (new \InvalidArgumentException("Invalid username or password.", 401));
		}

		//grab profile by profileId from MySQL and put into the session
		$profile = Profile::getProfileByProfileId($pdo, $profile->getProfileId());

		//check if user still has an outstanding activation token. User must validate token before signing in.
		if(!empty($profile->getProfileActivationToken()) || $profile->getProfileActivationToken() !== null) {throw (new \RuntimeException("Please check your email to activate your account before logging in.", 403));
		}

		//add profile to session upon successful sign-in
		$_SESSION["profile"] = $profile;
		//create the auth payload
		$authObject = (object) [
			"profileId" => $profile->getProfileId(),
			"profileUsername" => $profile->getProfileUsername()
		];

		//create & set the JWT
		setJwtAndAuthHeader("auth", $authObject);

		//update reply
		$reply->message = "Welcome! Sign in successful.";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP request!"));
	}
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

//sets up the response header
header("Content-type: application/json");

//lastly, JSON encode the $reply object and echo it back to the front end.
echo json_encode($reply);
