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

//	Including the example below from class materials; Erin will clean up upon writing constructor method

//	/**
//	 * constructor for this Tweet
//	 *
//	 * @param string|Uuid $newTweetId id of this Tweet or null if a new Tweet
//	 * @param string|Uuid $newTweetProfileId id of the Profile that sent this Tweet
//	 * @param string $newTweetContent string containing actual tweet data
//	 * @param \DateTime|string|null $newTweetDate date and time Tweet was sent or null if set to current date and time
//	 * @throws \InvalidArgumentException if data types are not valid
//	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
//	 * @throws \TypeError if data types violate type hints
//	 * @throws \Exception if some other exception occurs
//	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
//	 **/
//	public function __construct($newTweetId, $newTweetProfileId, string $newTweetContent, $newTweetDate = null) {
//		try {
//			$this->setTweetId($newTweetId);
//			$this->setTweetProfileId($newTweetProfileId);
//			$this->setTweetContent($newTweetContent);
//			$this->setTweetDate($newTweetDate);
//		}
//			//determine what exception type was thrown
//		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
//			$exceptionType = get_class($exception);
//			throw(new $exceptionType($exception->getMessage(), 0, $exception));
//		}
//	}

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
public function setBookmarkArtId ($new)






}