<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\AbqStreetArt\\;

/**
 * API to check profile activation status
 *
 * @author Nathaniel Gustafson <natjgus@gmail.com> and Abq Street Art
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
	//make sure "id" is changed to "token" on line 5 of .htaccess (why?!)
	$token = filter_input(INPUT_GET, "token", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
}

