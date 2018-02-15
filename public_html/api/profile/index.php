<?php

require_once (dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once (dirname(__DIR__, 3). "/php/classes/autoload.php");
require_once(dirname(__DIR__, 3) . "/php/lib/jwt.php");
require_once(dirname(__DIR__, 3) . "/php/lib/xsrf.php");
require_once(dirname(__DIR__, 3) . "/php/lib/uuid.php");

//require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqStreetArt\ {
    Profile
};

/**
 * API for profile
 *
 * @author Mary MacMillan <mschmitt5@cnm.edu>
 * @author Gkephart
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
    $pdo = connectToEncryptedMySQL("etc/apache2/capstone-mysql/streetart.ini");

    //determine which HTTP method was used
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    //sanitize input (id is equivalent to profileId and the id for what the user thinks of as a page
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $profileUserName = filter_input(INPUT_GET, "profileUserName", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
    $profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    //make sure the id is valid for methods that require it
    if (($method === "PUT") && (empty($id) === true)) {
        throw (new InvalidArgumentException("id cannot be empty or negative", 405));
    }

    if ($method === "GET") {

        //set XSRF cookie
        setXsrfCookie();

        //gets a post by...nothing?
        //TODO I really don't know what to do here...
        if(empty($id) === false) {
            $profile = Profile::getProfileByProfileId($pdo, $id);
            if ($profile !== null) {
                $reply->data = $profile;
            }
        }

    }



}