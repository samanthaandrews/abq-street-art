<?php
namespace Edu\Cnm\AbqStreetArt;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 *
 *
 *
 * @author Samantha Andrews <samantharaeandrews@gmail.com>
 * @version
 **/
class Art implements \JsonSerializable {
	use ValidateUuid;
	/**
	 * id for this Art; this is the primary key
	 * @var Uuid $artId
	 **/
	private $artId;
	/**
	 * street address for this piece of Art
	 * @var string $artAddress
	 **/
	private $artAddress;
	/**
	 * name of artist who made this piece of Art
	 * @var string $artArtist
	 **/
	private $artArtist;
	/**
	 * url of the photo corresponding to this piece of Art
	 * @var string $artImageUrl
	 **/
	private $artImageUrl;
	/**
	 * latitude coordinate of this piece of Art
	 * @var float $artLat;
	 **/
	private $artLat;
	/**
	 * detailed description of where this Art is located
	 * @var string $artLocation
	 **/
	private $artLocation;
	/**
	 * longitude coordinate of this piece of Art
	 * @var float $artLong
	 **/
	private $artLong;
	/**
	 * title of this piece of Art
	 * @var string $artTitle
	 **/
	private $artTitle;
	/**
	 * type or category of this Art
	 * @var string $artType
	 **/
	private $artType;
	/**
	 * four digit year this piece of Art was made
	 * @var integer $artYear
	 **/
	private $artYear;

/**
 * accessor method for art id
 *
 * @return Uuid value of art id
 **/
public function getArtId() : Uuid {
	return($this->artId);
}

/**
 * mutator method for art id
 *
 * @param Uuid/string $newArtId new value of art id
 * @throws \RangeException if $newArtId is not positive
 * @throws \TypeError if $newArtId is not a uuid or string
 **/
public function setArtId( $newArtId) : void {
	try {
		$uuid = self::validateUuid($newArtId);
	} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exceptionType($exception->getMessage(), 0, $exception));
	}

	// convert and store the art id
	$this->artId = $uuid;
}

	/**
	 * accessor method for art address
	 *
	 * @return string value of art address
	 **/
	public function getArtAddress() : string {
		return($this->artAddress);
	}

	/**
	 * mutator method for art address
	 *
	 * @param string $newArtAddress new value of art address
	 * @throws \InvalidArgumentException if $newArtAddress is not a string or insecure
	 * @throws \RangeException if $newArtAddress is > 200 characters
	 * @throws \TypeError if $newArtAddress is not a string
	 **/
	public function setArtAddress(string $newArtAddress) : void {
		// verify the address is secure
		$newArtAddress = trim($newArtAddress);
		$newArtAddress = filter_var($newArtAddress, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtAddress) === true) {
			throw(new \InvalidArgumentException("art address is empty or insecure"));
		}

		// verify the address will fit in the database
		if(strlen($newArtAddress) > 200) {
			throw(new \RangeException("art address too large"));
		}

		// store the address
		$this->artAddress = $newArtAddress;
	}

	/**
	 * accessor method for art artist
	 *
	 * @return string value of art artist
	 **/
	public function getArtArtist() :string {
		return($this->artArtist);
	}

	/**
	 * mutator method for art artist
	 *
	 * @param string $newArtArtist new value of art artist
	 * @throws \InvalidArgumentException if $newArtArtist is not a string or insecure
	 * @throws \RangeException if $newArtArtist is > 200 characters
	 * @throws \TypeError if $newArtArtist is not a string
	 **/
	public function setArtArtist(string $newArtArtist) : void {
		// verify the artist is secure
		$newArtArtist = trim($newArtArtist);
		$newArtArtist = filter_var($newArtArtist, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtArtist) === true) {
			throw(new \InvalidArgumentException("art artist is empty or insecure"));
		}

		// verify the artist will fit in the database
		if(strlen($newArtArtist) > 200) {
			throw(new \RangeException("art artist too large"));
		}

		// store the artist
		$this->artArtist = $newArtArtist;
	}

	/**
	 * accessor method for art image url
	 *
	 * @return string value of art image url
	 **/
	public function getArtImageUrl() : string {
		return($this->artImageUrl);
	}

	/**
	 * mutator method for art image url
	 *
	 * @param string $newArtImageUrl new value of art artist
	 * @throws \InvalidArgumentException if $newArtImageUrl is not a string or insecure
	 * @throws \RangeException if $newArtImageUrl is > 200 characters
	 * @throws \TypeError if $newArtImageUrl is not a string
	 **/
	public function setArtImageUrl(string $newArtImageUrl) : void {
		// verify the url is secure
		$newArtImageUrl = trim($newArtImageUrl);
		$newArtImageUrl = filter_var($newArtImageUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtImageUrl) === true) {
			throw(new \InvalidArgumentException("image url is empty or insecure"));
		}

		// verify the image url will fit in the database
		if(strlen($newArtImageUrl) > 200) {
			throw(new \RangeException("image url too large"));
		}

		// store the image url
		$this->artImageUrl = $newArtImageUrl;
	}

	/** accessor method for art latitude
	 *
	 * @return float value of art latitude
	 **/
	public function getArtLat() : float {
		return($this->artLat);
	}

	/** mutator method for art latitude
	 *
	 * @param float $newArtLat new value of art latitude
	 * @throws \InvalidArgumentException if $newArtLat is not a string or insecure
	 * @
	 **/


	/**
	 * accessor method for art location
	 *
	 * @return string value of art location
	 **/
	public function getArtLocation() :string {
		return($this->artLocation);
	}

	/**
	 * mutator method for art location
	 *
	 * @param string $newArtLocation new value of art location
	 * @throws \InvalidArgumentException if $newArtLocation is not a string or insecure
	 * @throws \RangeException if $newArtLocation is > 200 characters
	 * @throws \TypeError if $newArtLocation is not a string
	 **/
	public function setArtLocation(string $newArtLocation) : void {
		// verify the art location is secure
		$newArtLocation = trim($newArtLocation);
		$newArtLocation = filter_var($newArtLocation, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtLocation) === true) {
			throw(new \InvalidArgumentException("art location is empty or insecure"));
		}

		// verify the location will fit in the database
		if(strlen($newArtLocation) > 200) {
			throw(new \RangeException("art location too large"));
		}

		// store the artist
		$this->artLocation = $newArtLocation;
	}

	/**
	 * accessor method for art title
	 *
	 * @return string value of art title
	 **/
	public function getArtTitle() : string {
		return($this->artTitle);
	}

	/**
	 * mutator method for art title
	 *
	 * @param string $newArtTitle new value of art title
	 * @throws \InvalidArgumentException if $newArtTitle is not a string or insecure
	 * @throws \RangeException if $newArtTitle is > 200 characters
	 * @throws \TypeError if $newArtTitle is not a string
	 **/
	public function setArtTitle(string $newArtTitle) : void {
		// verify the title is secure
		$newArtTitle = trim($newArtTitle);
		$newArtTitle = filter_var($newArtTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtTitle) === true) {
			throw(new \InvalidArgumentException("art title is empty or insecure"));
		}

		// verify the art title will fit in the database
		if(strlen($newArtTitle) > 200) {
			throw(new \RangeException("art title too large"));
		}

		// store the title
		$this->artTitle = $newArtTitle;
	}

	/**
	 * accessor method for art type
	 *
	 * @return string value of art type
	 **/
	public function getArtType() : string {
		return($this->artType);
	}

	/**
	 * mutator method for art type
	 *
	 * @param string $newArtType new value of art type
	 * @throws \InvalidArgumentException if $newArtType is not a string or insecure
	 * @throws \RangeException if $newArtType is > 200 characters
	 * @throws \TypeError if $newArtType is not a string
	 **/
	public function setArtType(string $newArtType) : void {
		// verify the art type is secure
		$newArtType = trim($newArtType);
		$newArtType = filter_var($newArtType, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newArtType) === true) {
			throw(new \InvalidArgumentException("art type is empty or insecure"));
		}

		// verify the art type will fit in the database
		if(strlen($newArtType) > 200) {
			throw(new \RangeException("art type too large"));
		}

		// store the art type
		$this->artType = $newArtType;
	}

