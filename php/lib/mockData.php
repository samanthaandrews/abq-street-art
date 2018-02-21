<?php

require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once("uuid.php");
require_once dirname(__DIR__, 1) . "/classes/autoload.php";
require_once dirname(__DIR__, 2) . "/vendor/autoload.php";

use Edu\Cnm\AbqStreetArt\{
	Art
};
$VALID_ARTADDRESS = "3900 Artsy Fartsy Lane NE";

$VALID_ARTADDRESS2 = "this is still a valid address for this art";

$VALID_ARTARTIST = "Mr. Art Man";

$VALID_ARTIMAGEURL = "http://artsyfartsy.org";

$VALID_ARTLAT = 35.0931;

 $VALID_ARTLOCATION = "in the alley where everyone pees";

 $VALID_ARTLONG = -106.6641772;

 $VALID_ARTTITLE = "A Very Nice ART!";

 $VALID_ARTTYPE = "mural";

 $VALID_ARTYEAR = 1992;

//grab the mySQL connection
$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $VALID_ARTADDRESS, $VALID_ARTARTIST, $VALID_ARTIMAGEURL, $VALID_ARTLAT, $VALID_ARTLOCATION, $VALID_ARTLONG, $VALID_ARTTITLE, $VALID_ARTTYPE, $VALID_ARTYEAR);
$art->insert($pdo);


// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $VALID_ARTADDRESS, $VALID_ARTARTIST, $VALID_ARTIMAGEURL, 35.093400000000003, $VALID_ARTLOCATION, -106.66681850000001, $VALID_ARTTITLE, "public sculpture", $VALID_ARTYEAR);
$art->insert($pdo);



// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $VALID_ARTADDRESS, $VALID_ARTARTIST, $VALID_ARTIMAGEURL, 35.087600000000002, $VALID_ARTLOCATION, -106.6494, $VALID_ARTTITLE, "photographs (visual works)", $VALID_ARTYEAR);
$art->insert($pdo);