<?php

require_once (dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once (dirname(__DIR__, 3). "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqStreetArt\ {
    Profile
};

/**
 * API for profile
 *
 * @author Mary MacMillan <mschmitt5@cnm.edu>
 * @author Gkephart
 * @author Rochelle Lewis <rlewis37@cnm.edu>
 **/

//verify the session, if it is not active, start it
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//prepare empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    //get mySQL connection
    $pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

    //determine which HTTP method was used
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    //sanitize input (id is equivalent to profileId and the id for what the user thinks of as a page
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    // TODO do I need activation token? Rochelle has it - George does not.
    $profileActivationToken = filter_input(INPUT_GET,"profileActivationToken", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $profileUserName = filter_input(INPUT_GET, "profileUserName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    //make sure the id is valid for methods that require it
    if (($method === "PUT") && (empty($id) === true)) {
        throw (new InvalidArgumentException("id cannot be empty or negative", 405));
    }

    if ($method === "GET") {

        //set XSRF cookie
        setXsrfCookie();

        //gets a post by profile id
        if(empty($id) === false) {
            $profile = Profile::getProfileByProfileId($pdo, $id);
            if ($profile !== null) {
                $reply->data = $profile;
            }
        } elseif (empty($profileActivationToken) === false) {
            $profile = Profile::getProfileByProfileActivationToken($pdo, $profileActivationToken);
            if ($profile !== null) {
                $reply->data = $profile;
            }
        }else if(empty($profileEmail) === false) {
            $profile = Profile::getProfileByProfileEmail($pdo, $profileEmail);
            if($profile !== null) {
                $reply->data = $profile;
            }
        }else if(empty($profileUserName) === false) {
            $profile = Profile::getProfileByProfileUserName($pdo, $profileUserName);
            if ($profile !== null) {
                $reply->data = $profile;
            }
        }

    } elseif($method === "PUT") {
        //enforce that the XSRF token is present in the header
        verifyXsrf();


        //enforce the user is signed in and only trying to edit their own profile
        if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $id) {
            throw(new \InvalidArgumentException("You do not have access!", 403));
        }
        validateJwtHeader();

        //decode the response from the front end
        $requestContent = file_get_contents("php://input");
        $requestObject = json_decode($requestContent);

        //retrieve the profile to be updated
        $profile = Profile::getProfileByProfileId($pdo, $id);
        if($profile === null) {
            throw(new RuntimeException("Profile does not exist", 404));
        }

        //profile email is a required field
        if(empty($requestObject->profileEmail) === true) {
            throw(new \InvalidArgumentException ("No profile email present", 405));
        }

        //profile userName
        if(empty($requestObject->profileUserName) === true) {
            $requestObject->ProfileUserName = $profile->getProfileUserName();
        }

        $profile->setProfileEmail($requestObject->profileEmail);
        $profile->setProfileUserName($requestObject->profileUserName);
        $profile->update($pdo);

        // update reply
        $reply->message = "Profile information updated";


        //change password if requested and all required fields are passed
        if(($requestObject->currentProfilePassword !== null) && ($requestObject->newProfilePassword !== null) && ($requestObject->newProfileConfirmPassword !== null)) {

            //throw exception if current password given doesn't hash to match the current password!
            $currentPasswordHash = hash_pbkdf2("sha512", $requestObject->currentProfilePassword, $profile->getProfileSalt(), 262144);
            if($currentPasswordHash !== $profile->getProfileHash()) {
                throw (new \RuntimeException("Current password is incorrect.", 401));
            }

            //throw exception if new password confirmation field doesn't match
            if($requestObject->newProfilePassword !== $requestObject->newProfileConfirmPassword) {
                throw (new \RuntimeException("New passwords do not match", 401));
            }

            //generate new salt and hash for new password
            $newProfileSalt = bin2hex(random_bytes(32));
            $newProfileHash = hash_pbkdf2("sha512", $requestObject->newProfilePassword, $newProfileSalt, 262144);
            //update password
            $profile->setProfileSalt($newProfileSalt);
            $profile->setProfileHash($newProfileHash);
        }
    } elseif($method === "DELETE") {

        //verify the XSRF Token
        verifyXsrf();

        //enforce the end user has a JWT token
        validateJwtHeader();
        $profile = Profile::getProfileByProfileId($pdo, $id);
        if($profile === null) {
            throw (new RuntimeException("Profile does not exist"));
        }

        //enforce the user is signed in and only trying to edit their own profile
        if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $profile->getProfileId()->toString()) {
            throw(new \InvalidArgumentException("You do not have access!", 403));
        }
		 validateJwtHeader();

        //delete the profile from the database
        $profile->delete($pdo);
        $reply->message = "Profile Deleted";
    } else {
        throw (new InvalidArgumentException("Invalid HTTP request", 400));
    }

    // catch any exceptions that were thrown and update the status and message state variable fields
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

