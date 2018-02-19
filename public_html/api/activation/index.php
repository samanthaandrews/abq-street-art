<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";

use Edu\Cnm\AbqStreetArt\ {
	Profile
};

/**
 * API to check profile activation status
 *
 * @author Nathaniel Gustafson <natjgus@gmail.com> and Abq Street Art
 *
 **/

//Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}
/**
 * prepare an empty reply
 *
 * Need to create a new stdClass named $reply. A stdClass is an empty container that we can use to store things
 *
 * We wuill use this object named $reply to store the results of the call to our API. The status 200 line adds a state variable
 **/
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;


try {

	//grab the database connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

	//determine which HTTP method, store the result in $method
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize and store activation token
	//make sure "id" is changed to "activation" on line 5 of .htaccess (why?!) Rochelle did this in her example, Data Design has no .htacces for activation
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the activation token is the correct size
	if(strlen($activation) !== 32 ){
		throw(new InvalidArgumentException("activation is not correct length", 405));
	}

	// verify that the activation token is a string value of a hexadecimal
	if(ctype_xdigit($activation) === false) {
		throw (new \InvalidArgumentException("activation is empty or has invalid content", 405));
	}

	// handle The GET HTTP request
	if($method === "GET"){

		// set XSRF Cookie
		setXsrfCookie();
		//find profile associated with the activation token
		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);

		//verify the profile is not null
		if($profile !== null){

			//make sure the activation token matches
			if($activation === $profile->getProfileActivationToken()) {

				//set activation to null
				$profile->setProfileActivationToken(null);

				//update the profile in the database
				$profile->update($pdo);

				//set the reply for the end user
				$reply->data = "Your profile is activated";
			}
		} else {
			//throw an exception if the activation token does not exist
			throw(new RuntimeException("Profile with this activation code does not exist", 404));
		}
	} else {
		//throw an exception if the HTTP request is not a GET
		throw(new InvalidArgumentException("Invalid HTTP method request", 403));
	}

		//update the reply objects status and message state variables if an exception or type exception was thrown;
} catch (Exception $exception){
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

//prepare and send the reply
header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}


