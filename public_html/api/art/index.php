<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";

use Edu\Cnm\AbqStreetArt\{
	Art
};
/**
 * * api for Art class
 *
 * @author Samantha Andrews samantharaeandrews@gmail.com, George Kephart and Abq Street Art
 **/
//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}
//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	//grab the mySQL connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");
	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];
	//stores the Primary Key ($artId) for the GET method in $id. This key will come in the URL sent by the front end. If no key is present, $id will remain empty. Note that the input is filtered.
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$artType = filter_input(INPUT_GET, "artType", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$distance = filter_input(INPUT_GET, "distance", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLat = filter_input(INPUT_GET, "userLat", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	$userLong = filter_input(INPUT_GET, "userLong", FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
	// handle GET request - if id is present, that art is returned, otherwise all arts are returned


	if($method === "GET") {


		//set XSRF cookie
		setXsrfCookie();
		//get a specific art or all arts and update reply
		if(empty($id) === false) {
			$art = Art::getArtByArtId($pdo, $id);
			if($art !== null) {
				$reply->data = $art;
			}
			//TODO figure out if this part is correct
		} else if(empty($userLat) === false && empty($userLong) === false && empty($distance) === false) {
			$arts = Art::getArtByDistance($pdo, $userLong, $userLat, $distance)->toArray();
			if($arts !== null) {
				$reply->data = $arts;
			}
		} else if(empty($artType) === false) {
			$arts = Art::getArtByArtType($pdo, $artType)->toArray();
			if($arts !== null) {
				$reply->data = $arts;
			}
		}
		else {
			$arts = Art::getAllArts($pdo)->toArray();
			if($arts !== null) {
				$reply->data = $arts;
			}
		}
		// If the method request is not GET an exception is thrown
	} else {
		throw (new InvalidArgumentException("Invalid HTTP Method Request", 418));
	}
// update reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}
// In these lines, the Exceptions are caught and the $reply object is updated with the data from the caught exception. Note that $reply->status will be updated with the correct error code in the case of an Exception.
header("Content-type: application/json");
// sets up the response header.
if($reply->data === null) {
	unset($reply->data);
}
echo json_encode($reply);