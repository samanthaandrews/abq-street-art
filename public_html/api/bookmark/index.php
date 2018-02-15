<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "../php/classes/autoload.php";

// TODO: not sure where to get the file below that George included in the Like API example. Commenting out for now. -Erin 2/15
//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once dirname(__DIR__, 3) . "../php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "../php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "../php/lib/uuid.php";

use Edu\Cnm\AbqStreetArt\{
	Bookmark
};

/**
 * Api for the Bookmark class
 *
 * @author george kephart
 * @author Erin Scott
 */

//verify the session, start session if not already active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize the search parameters
	$bookmarkArtId = $id = filter_input(INPUT_GET, "bookmarkArtId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	$bookmarkProfileId = $id = filter_input(INPUT_GET, "bookmarkProfileId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
	if($method === "GET") {

		//set XSRF cookie
		setXsrfCookie();

		//gets the specific bookmark that is associated, based on its composite key
		if ($bookmarkArtId !== null && $bookmarkProfileId !== null) {
			$bookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($pdo, $bookmarkArtId, $bookmarkProfileId);
			if($bookmark!== null) {
				$reply->data = $bookmark;
			}

			//if none of the search parameters are met, throw an exception
		} else if(empty($bookmarkArtId) === false) {
			$bookmark = Bookmark::getBookmarkByBookmarkArtId($pdo, $bookmarkArtId)->toArray();
			if($bookmark !== null) {
				$reply->data = $bookmark;
			}

			//get all of the bookmarks associated with the profileId
		} else if(empty($bookmarkProfileId) === false) {
			$bookmark = Bookmark::getBookmarkByBookmarkProfileId($pdo, $bookmarkProfileId)->toArray();
			if($bookmark !== null) {
				$reply->data = $bookmark;
			}

		} else {
			throw new InvalidArgumentException("incorrect search parameters ", 404);
		}
	} else if($method === "POST" || $method === "PUT") {

		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->likeProfileId) === true) {
			throw (new \InvalidArgumentException("No Profile linked to the Like", 405));
		}
		if(empty($requestObject->likeTweetId) === true) {
			throw (new \InvalidArgumentException("No tweet linked to the Like", 405));
		}
		if(empty($requestObject->likeDate) === true) {
			$requestObject->LikeDate =  date("y-m-d H:i:s");
		}
		if($method === "POST") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();
			// enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in too like posts", 403));
			}

			//validateJwtHeader();
			$like = new Like($_SESSION["profile"]->getProfileId(), $requestObject->likeTweetId);
			$like->insert($pdo);
			$reply->message = "liked tweet successful";
		} else if($method === "PUT") {

			//enforce the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();
			//grab the like by its composite key
			$like = Like::getLikeByLikeTweetIdAndLikeProfileId($pdo, $requestObject->likeProfileId, $requestObject->likeTweetId);
			if($like === null) {
				throw (new RuntimeException("Like does not exist"));
			}

			//enforce the user is signed in and only trying to edit their own bookmark
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $like->getLikeProfileId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this tweet", 403));
			}

			//validateJwtHeader();
			//preform the actual delete
			$like->delete($pdo);

			//update the message
			$reply->message = "Like successfully deleted";
		}

		// if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	}

	//catch any exceptions that is thrown, and update the reply status and message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

// encode and return reply to front end caller
echo json_encode($reply);