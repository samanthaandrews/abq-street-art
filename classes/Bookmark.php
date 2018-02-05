<?php
namespace Edu\Cnm\AbqStreetArt;


require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is the cross section for the Bookmark class of ABQ Street Art capstone project.
 *
 * @author Erin Scott <erinleeannscott@gmail.com>
 *
 * @version 1.0
 **/

class Bookmark implements \JsonSerialable {
	use ValidateUuid;

	/**
	 * the id of the artwork that this bookmark is tied to; this is a foreign key
	 **/
	private $bookmarkArtId;

	/**
	 * the id of the profile that this bookmark is tied to; this is a foreign key
	 **/
	private $bookmarkProfileId;

	/**
	 * constructor for this Bookmark
	 *
	 * Erin still needs to write this as of 2/5
	 **/


}