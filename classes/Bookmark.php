<?php
namespace Edu\Cnm\AbqStreetArt;


require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is the cross section for the Bookmark class of ABQ Street Art capstone project.
 *
 * @author Erin Scott <erinleeannscott@gmail.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
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
	 * @param string|Uuid $newBookmarkArtId id of this Bookmark or null if a new Bookmark
	 * @param string|Uuid $newBookmarkProfileId id of this Bookmark or null if a new Bookmark
	 *
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct ($newBookmarkArtId, $newBookmarkProfileId) {
		try {
			$this->setBookmarkArtId($newBookmarkArtId);
			$this->setBookmarkProfileId($newBookmarkProfileId);
		}
		//determines what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for bookmarkArtId
	 *
	 * @return Uuid value of artId
	 **/
	public function getArtId() : Uuid {
		return($this->artId);
	}

	/**
	 * mutator method for bookmarkArtId
	 *
	 * @param Uuid|string $newBookmarkArtId for new value of bookmarkArtId
	 * @throws \RangeException if $newBookmarkArtId is not positive
	 * @throws \TypeError if $newBookmarkArtId is not a Uuid or string
	 **/
public function setBookmarkArtId ( $newBookmarkArtId) : void {
	try {
		$uuid = self::validateUuid($newBookmarkArtId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}

	// convert and store the bookmarkArtId
	$this->bookmarkArtId = $uuid;
}

	/**
	 * accessor method for bookmarkProfileId
	 *
	 * @return Uuid value of profileId
	 **/
	public function getProfileId() : Uuid {
		return($this->profileId);
	}

	/**
	 * mutator method for bookmarkProfileId
	 *
	 * @param Uuid|string $newBookmarkProfileId for new value of bookmarkProfileId
	 * @throws \RangeException if $newBookmarkProfileId is not positive
	 * @throws \TypeError if $newBookmarkProfileId is not a Uuid or string
	 **/
	public function setBookmarkProfileId ( $newBookmarkProfileId) : void {
		try {
			$uuid = self::validateUuid($newBookmarkProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the bookmarkProfileId
		$this->bookmarkProfileId = $uuid;
	}


/**
 * inserts this Bookmark class into mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/

/**
 * deletes this Bookmark class from mySQL
 *
 * @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/

/**
 * updates this Bookmark class in mySQL
 *
 *  @param \PDO $pdo PDO connection object
 * @throws \PDOException when mySQL related errors occur
 * @throws \TypeError if $pdo is not a PDO connection object
 **/

/**
 * formats the state variables for JSON serialization
 *
 * @return array resulting state variables to serialize
 **/
public function jsonSerialize() : array {
	$fields = get_object_vars($this);

	$fields["bookmarkArtId"] = $this->bookmarkArtId->toString();
	$fields["bookmarkProfileId"] = $this->bookmarkProfileId->toString();
}

}