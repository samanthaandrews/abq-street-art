<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";


require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";

use Edu\Cnm\AbqStreetArt\{
	Bookmark, Art, Profile
};

/**
 * Api for the Bookmark class
 *
 * @author George Kephart
 * @author Erin Scott <erinleeannscott@gmail.com>
 **/

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
	$bookmarkArtId = filter_input(INPUT_GET, "bookmarkArtId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	$bookmarkProfileId = filter_input(INPUT_GET, "bookmarkProfileId", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);

	/**
	 * Get API for Bookmark
	 **/

	if($method === "GET") {

		//set XSRF cookie
		setXsrfCookie();

		//gets the specific bookmark that is associated, based on its composite key (get by both)
		if ($bookmarkArtId !== null && $bookmarkProfileId !== null) {
			$bookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($pdo, $bookmarkArtId, $bookmarkProfileId);
			if($bookmark!== null) {
				$reply->data = $bookmark;
			}

			//get all of the bookmarks associated with the artId
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

			//if none of the search parameters are met, throw an exception
		} else {
			throw new InvalidArgumentException("invalid search parameters ", 404);
		}

	/**
	 * Post API for Bookmark
	 **/

	} else if($method === "POST") {
		//enforce that the end user has a XSRF token.
		verifyXsrf();

		//enforce the end user has a JWT token
		validateJwtHeader();

		//decode the response from the frontend
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->bookmarkProfileId) === true) {
			throw (new \InvalidArgumentException("No profile linked to the bookmark", 405));
		}
		if(empty($requestObject->bookmarkArtId) === true) {
			throw (new \InvalidArgumentException("No art linked to the bookmark", 405));
		}

//TODO: We may need to delete this or fix it...we don't know. Ask Dylan.
//		if(Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($pdo, $_SESSION["profile"]->getProfileId(), $requestObject->bookmarkArtId)!==null){
//			throw(new \InvalidArgumentException("The Bookmark already exists."));
//		}
//		if (Art::getArtByArtId($pdo,$requestObject->bookmarkArtId)===null){
//			throw(new \InvalidArgumentException("The art does not exist."));
//		}

			//enforce that the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("You must be logged in to bookmark pieces of art", 403));
			}


			$bookmark = new Bookmark($requestObject->bookmarkArtId, $_SESSION["profile"]->getProfileId());
			$bookmark->insert($pdo);
			echo("116");
			$reply->message = "Successfully bookmarked this piece of art";

	}

	/**
	 * Put API for Bookmark
	 *
	 * You can only delete only id, thus we have to use a PUT.
	 **/
	else if($method === "PUT") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			// retrieve the Bookmark to be deleted
			$bookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($pdo, $bookmarkArtId, $bookmarkProfileId);
			if($bookmark === null) {
				throw(new RuntimeException("Bookmark does not exist", 404));
			}

			//enforce that the user is signed in and only trying to edit their own bookmark
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $bookmark->getBookmarkProfileId()->toString()) {
			throw(new \InvalidArgumentException("You are not allowed to delete this bookmark", 403));
		}

			//TODO: Am I missing something to correspond with the comments from the code examples below?? -Erin 2/15
			//enforce the end user has a JWT token
			validateJwtHeader();


			// delete bookmark
			$bookmark->delete($pdo);

			// update reply message to user
			$reply->message = "Bookmark successfully deleted";

	// if any other HTTP request is sent throw an exception
	} else {
		throw (new InvalidArgumentException("Invalid HTTP request", 418));
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
