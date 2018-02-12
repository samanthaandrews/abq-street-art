<?php
namespace Edu\Cnm\AbqStreetArt;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 *
 * This is the Art class where one will find data about each piece of art such as address, artist, image url, latitude and longitude, location, title, type, and year.
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
	 * constructor for this Art
	 *
	 * @param UUID|string $newArtId id of this Art or null if a new Art
	 * @param string $newArtAddress address of this Art
	 * @param string $newArtArtist artist of this Art
	 * @param string $newArtImageUrl image url of this Art
	 * @param string $newArtLat latitude value of this art
	 * @param string $newArtLocation location of this art
	 * @param string $newArtLong longitude value of this art
	 * @param string $newArtTitle title of this art
	 * @param string $newArtType type of this art
	 * @param string $newArtYear year this art was made
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newArtId, string $newArtAddress, string $newArtArtist, string $newArtImageUrl, float $newArtLat, string $newArtLocation, float $newArtLong, string $newArtTitle, string $newArtType, int $newArtYear) {
		try {
			$this->setArtId($newArtId);
			$this->setArtAddress($newArtAddress);
			$this->setArtArtist($newArtArtist);
			$this->setArtImageUrl($newArtImageUrl);
			$this->setArtLat($newArtLat);
			$this->setArtLocation($newArtLocation);
			$this->setArtLong($newArtLong);
			$this->setArtTitle($newArtTitle);
			$this->setArtType($newArtType);
			$this->setArtYear($newArtYear);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

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
 * @param Uuid|string $newArtId new value of art id
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
	 *
	 * @param string $newArtImageUrl new value of art artist
	 * @throws \InvalidArgumentException if $newArtImageUrl is not a string or insecure
	 * @throws \RangeException if $newArtImageUrl is > 200 characters
	 * @throws \TypeError if $newArtImageUrl is not a string
	 **/
	public function setArtImageUrl(string $newArtImageUrl) : void {
		// verify the url is secure
		$newArtImageUrl = trim($newArtImageUrl);
		$newArtImageUrl = filter_var($newArtImageUrl, FILTER_SANITIZE_URL);
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
	 *
	 * @return float value of art latitude
	 **/
	public function getArtLat() : float {
		return($this->artLat);
	}

	/** mutator method for art latitude
	 *
	 * @param float $newArtLat new value of art latitude
	 * @throws \InvalidArgumentException if $newArtLat is not a float or insecure
	 * @throws \RangeException if $newArtLat is not within -90 to 90
	 * @throws \TypeError if $newArtLat is not a float
	 **/

	public function setArtLat(float $newArtLat) : void {
	// verify the latitude exists on earth
	if(floatval($newArtLat) > 90) {
		throw(new \RangeException("art latitude is not between -90 and 90"));
	}
	if (floatval($newArtLat) < -90) {
		throw(new \RangeException("art latitude is not between -90 and 90"));
	}
		// store the latitude
		$this->artLat = $newArtLat;
}

	/**
	 * accessor method for art location
	 *
	 * @return string value of art location
	 **/
	public function getArtLocation() : string {
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

	/** accessor method for art longitude
	 *
	 *
	 * @return float value of art longitude
	 **/
	public function getArtLong() : float {
		return($this->artLong);
	}

	/** mutator method for art longitude
	 *
	 * @param float $newArtLong new value of art longitude
	 * @throws \InvalidArgumentException if $newArtLong is not a float or insecure
	 * @throws \RangeException if $newArtLong is not within -180 to 180
	 * @throws \TypeError if $newArtLong is not a float
	 **/

	public function setArtLong(float $newArtLong) : void {
		// verify the latitude exists on earth
		if(floatval($newArtLong) > 180) {
			throw(new \RangeException("art longitude is not between -180 and 180"));
		}
		if (floatval($newArtLong) < -180) {
			throw(new \RangeException("art longitude is not between -180 and 180"));
		}
		// store the latitude
		$this->artLong = $newArtLong;
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

	/**
	 * accessor method for art year
	 *
	 * @return integer value of art year
	 **/
	public function getArtYear() : int {
		return($this->artYear);
	}

	/** mutator method for art year
	 *
	 * @param int $newArtYear new value of art year
	 * @throws \InvalidArgumentException if $newArtYear is not an integer or insecure
	 * @throws \RangeException if $newArtYear is below 0
	 * @throws \TypeError if $newArtYear is not an integer
	 **/
	public function setArtYear(int $newArtYear) : void {
		// verify the art year is secure
		$newArtYear = trim($newArtYear);
		$newArtYear = filter_var($newArtYear, FILTER_SANITIZE_NUMBER_INT);
		if(empty($newArtYear) === true) {
			throw(new \InvalidArgumentException("art year is empty or insecure"));
		}

		// verify the art year will fit in the database
		if(intval($newArtYear) < 1000) {
			throw(new \RangeException("art year before 1000"));
		}

		// store the art year
		$this->artYear = $newArtYear;
	}


	/**
	 * inserts this Art into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) : void {

		// create query template
		$query = "INSERT INTO art(artId, artAddress, artArtist, artImageUrl, artLat, artLocation, artLong, artTitle, artType, artYear) VALUES(:artId, :artAddress, :artArtist, :artImageUrl, :artLat, :artLocation, :artLong, :artTitle, :artType, :artYear)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["artId" => $this->artId->getBytes(), "artAddress" => $this->artAddress, "artArtist" => $this->artArtist, "artImageUrl" => $this->artImageUrl, "artLat" => $this->artLat, "artLocation" => $this->artLocation, "artLong" => $this->artLong, "artTitle" => $this->artTitle, "artType" => $this->artType, "artYear" => $this->artYear];
		$statement->execute($parameters);
	}


	/**
	 * deletes this Art from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) : void {

		// create query template
		$query = "DELETE FROM art WHERE artId = :artId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["artId" => $this->artId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this Art in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) : void {

		// create query template
		$query = "UPDATE art SET artAddress = :artAddress, artArtist = :artArtist, artImageUrl = :artImageUrl, artLat = :artLat, artLocation = :artLocation, artLong = :artLong, artTitle = :artTitle, artType = :artType, artYear = :artYear WHERE artId = :artId";
		$statement = $pdo->prepare($query);


		$parameters = ["artId" => $this->artId->getBytes(), "artAddress" => $this->artAddress, "artArtist" => $this->artArtist, "artImageUrl" => $this->artImageUrl, "artLat" => $this->artLat, "artLocation" => $this->artLocation, "artLong" => $this->artLong, "artTitle" => $this->artTitle, "artType" => $this->artType, "artYear" => $this->artYear];
		$statement->execute($parameters);
	}

	/**
	 * gets the Art by artId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $artId art id to search for
	 * @return Art|null Art found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 **/
	public static function getArtByArtId(\PDO $pdo, $artId) : ?Art {
		// sanitize the artId before searching
		try {
			$artId = self::validateUuid($artId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT artId, artAddress, artArtist, artImageUrl, artLat, artLocation, artLong, artTitle, artType, artYear FROM art WHERE artId = :artId";
		$statement = $pdo->prepare($query);

		// bind the art id to the place holder in the template
		$parameters = ["artId" => $artId->getBytes()];
		$statement->execute($parameters);

		// grab the art from mySQL
		try {
			$art = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$art = new Art($row["artId"], $row["artAddress"], $row["artArtist"], $row["artImageUrl"], $row["artLat"], $row["artLocation"], $row["artLong"], $row["artTitle"], $row["artType"], $row["artYear"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($art);
	}


	/**
	 * gets the Art by distance
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param float $userLat latitude coordinate of where user is
	 * @param float $userLong longitude coordinate of where user is
	 * @param float $distance distance in miles that the user is searching by
	 * @return \SplFixedArray SplFixedArray of pieces of art found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * **/

	public static function getArtByDistance(\PDO $pdo, float $userLong, float $userLat, float $distance) : \SplFixedArray {

		// create query template
		$query = "SELECT artId, artAddress, artArtist, artImageUrl, artLat, artLocation, artLong, artTitle, artType, artYear FROM art WHERE haversine(:userLong, :userLat, artLong, artLat) < :distance";
		$statement = $pdo->prepare($query);

		// bind the art distance to the place holder in the template
		$parameters = ["distance" => $distance, "userLat" => $userLat, "userLong" => $userLong];
		$statement->execute($parameters);

		// build an array of art
		$arts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$art = new Art($row["artId"], $row["artAddress"], $row["artArtist"], $row["artImageUrl"], $row["artLat"], $row["artLocation"], $row["artLong"], $row["artTitle"], $row["artType"], $row["artYear"]);
				$arts[$arts->key()] = $art;
				$arts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($arts);
	}

	/**
	 * gets the Art by type
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $artType art type to search for
	 * @return \SplFixedArray SplFixedArray of pieces of art found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getArtByArtType(\PDO $pdo, string $artType) : \SplFixedArray {
		// sanitize the description before searching
		$artType = trim($artType);
		$artType = filter_var($artType, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($artType) === true) {
			throw(new \PDOException("art type is invalid"));
		}

		// escape any mySQL wild cards
		$artType = str_replace("_", "\\_", str_replace("%", "\\%", $artType));

		// create query template
		$query = "SELECT artId, artAddress, artArtist, artImageUrl, artLat, artLocation, artLong, artTitle, artType, artYear FROM art WHERE artType LIKE :artType";
		$statement = $pdo->prepare($query);

		// bind the art type to the place holder in the template
		$artType = "%$artType%";
		$parameters = ["artType" => $artType];
		$statement->execute($parameters);

		// build an array of art
		$arts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$art = new Art($row["artId"], $row["artAddress"], $row["artArtist"], $row["artImageUrl"], $row["artLat"], $row["artLocation"], $row["artLong"], $row["artTitle"], $row["artType"], $row["artYear"]);
				$arts[$arts->key()] = $art;
				$arts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($arts);
	}

	/**
	 * gets all Arts
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of Arts found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllArts(\PDO $pdo) : \SPLFixedArray {
		// create query template
		$query = "SELECT artId, artAddress, artArtist, artImageUrl, artLat, artLocation, artLong, artTitle, artType, artYear FROM art";
		$statement = $pdo->prepare($query);
		$statement->execute();
		// build an array of arts
		$arts = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$art = new Art($row["artId"], $row["artAddress"], $row["artArtist"], $row["artImageUrl"], $row["artLat"], $row["artLocation"], $row["artLong"], $row["artTitle"], $row["artType"], $row["artYear"]);
				$arts[$arts->key()] = $art;
				$arts->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($arts);
	}


	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		$fields["artId"] = $this->artId->toString();
		return($fields);
	}

}