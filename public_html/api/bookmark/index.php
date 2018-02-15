<!--* Bookmark-->
<!--* DELETE by both (user and art). No mass deleting.-->
<!--* Be strict on IF blocks on DELETEs. Must have both user and art in order to delete anything-->

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

		// TODO: I'm not convinced that we need to include POST and PUT for bookmark - in my notes from our scrum, I
	} else if($method === "POST" || $method === "PUT") {

		//decode the response from the frontend
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);
		if(empty($requestObject->likeArtId) === true) {
			throw (new \InvalidArgumentException("No art linked to the bookmark", 405));
		}
		if(empty($requestObject->likeProfileId) === true) {
			throw (new \InvalidArgumentException("No profile linked to the bookmark", 405));
		}
		if($method === "POST") {

			//enforce that the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();

			//In George's "Like" example, it seems like maybe something was deleted here?? https://github.com/deepdivedylan/data-design/blob/master/public_html/api/like/index.php '

			//enforce that the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("You must be logged in to bookmark pieces of art", 403));
			}

			//validateJwtHeader();
			//TODO: is "validating the JWT header" actually what is happening below? The comments in the Like example seem to be off. https://github.com/deepdivedylan/data-design/blob/master/public_html/api/like/index.php
			$bookmark = new Bookmark($_SESSION["profile"]->getProfileId(), $requestObject->bookmarProfileId);
			$bookmark->insert($pdo);
			$reply->message = "Successfully bookmarked this piece of art";
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