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
}