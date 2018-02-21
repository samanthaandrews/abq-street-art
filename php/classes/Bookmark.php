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
 *
 * @see Dylan's Object Oriented PHP example: https://bootcamp-coders.cnm.edu/class-materials/object-oriented/object-oriented-php.php
 **/

class Bookmark implements \JsonSerializable {
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
	public function __construct($newBookmarkArtId, $newBookmarkProfileId) {
		try {
			$this->setBookmarkArtId($newBookmarkArtId);
			$this->setBookmarkProfileId($newBookmarkProfileId);
		} //determines what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for bookmarkArtId
	 *
	 * @return Uuid value of bookmarkArtId
	 **/
	public function getBookmarkArtId(): Uuid {
		return ($this->bookmarkArtId);
	}

	/**
	 * mutator method for bookmarkArtId
	 *
	 * @param Uuid|string $newBookmarkArtId for new value of bookmarkArtId
	 * @throws \RangeException if $newBookmarkArtId is not positive
	 * @throws \TypeError if $newBookmarkArtId is not a Uuid or string
	 **/
	public function setBookmarkArtId($newBookmarkArtId): void {
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
	 * @return Uuid value of bookmarkProfileId
	 **/
	public function getBookmarkProfileId(): Uuid {
		return ($this->bookmarkProfileId);
	}

	/**
	 * mutator method for bookmarkProfileId
	 *
	 * @param Uuid|string $newBookmarkProfileId for new value of bookmarkProfileId
	 * @throws \RangeException if $newBookmarkProfileId is not positive
	 * @throws \TypeError if $newBookmarkProfileId is not a Uuid or string
	 **/
	public function setBookmarkProfileId($newBookmarkProfileId): void {
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
	 * inserts this Bookmark into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// create query template
		$query = "INSERT INTO bookmark(bookmarkArtId, bookmarkProfileId) VALUES(:bookmarkArtId, :bookmarkProfileId)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$parameters = ["bookmarkArtId" => $this->bookmarkArtId->getBytes(), "bookmarkProfileId" => $this->bookmarkProfileId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Bookmark from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 *
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 *
	 * @see Example on Dylan's Github for Tweet Like: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Like.php
	 **/

	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM bookmark WHERE bookmarkArtId = :bookmarkArtId AND bookmarkProfileId = :bookmarkProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["bookmarkArtId" => $this->bookmarkArtId->getBytes(), "bookmarkProfileId" => $this->bookmarkProfileId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * gets the Bookmark by art id and profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param  $bookmarkArtId art id of this bookmark to search for
	 * @param  $bookmarkProfileId profile id of this bookmark to search for
	 *
	 * @return Bookmark|null Bookmark found or null if not found
	 *
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 *
	 * @see Example on Dylan's Github for Tweet Like: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Like.php
	 **/

	public static function getBookmarkByBookmarkArtIdAndBookmarkProfileId(\PDO $pdo, $bookmarkArtId, $bookmarkProfileId): ?Bookmark {

		// sanitize the bookmarkArtId before searching
		try {
			$bookmarkArtId = self::validateUuid($bookmarkArtId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// sanitize the bookmarkProfileId before searching
		try {
			$bookmarkProfileId = self::validateUuid($bookmarkProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT bookmarkArtId, bookmarkProfileId FROM bookmark WHERE bookmarkArtId = :bookmarkArtId AND bookmarkProfileId = :bookmarkProfileId";
		$statement = $pdo->prepare($query);

		// bind the art id and the profile id to the placeholder in the template
		$parameters = ["bookmarkArtId" => $bookmarkArtId->getBytes(), "bookmarkProfileId" => $bookmarkProfileId->getBytes()];
		$statement->execute($parameters);

		// grab the bookmark from MySQL
		try {
			$bookmark = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$bookmark = new Bookmark($row["bookmarkArtId"], $row["bookmarkProfileId"]);
			}
		} catch(\Exception $exception) {
			// if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($bookmark);
	}

	/**
	 * gets the Bookmark by art id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $bookmarkArtId art id of this bookmark to search for
	 *
	 * @return \SplFixedArray SplFixedArray of Bookmarks found or null if not found
	 *
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 *
	 * @see Example on Dylan's Github for Tweet Like: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Like.php
	 **/

	public static function getBookmarkByBookmarkArtId(\PDO $pdo, string $bookmarkArtId): \SPLFixedArray {
		try {
			$bookmarkArtId = self::validateUuid($bookmarkArtId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT bookmarkArtId, bookmarkProfileId FROM bookmark WHERE bookmarkArtId = :bookmarkArtId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the placeholders in the template
		$parameters = ["bookmarkArtId" => $bookmarkArtId->getBytes()];
		$statement->execute($parameters);

		// build an array of bookmarks
		$bookmarks = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$bookmark = new Bookmark($row["bookmarkArtId"], $row["bookmarkProfileId"]);
				$bookmarks[$bookmarks->key()] = $bookmark;
				$bookmarks->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($bookmarks);
	}

	/**
	 * gets the Bookmark by profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $bookmarkProfileId profile id of this bookmark to search for
	 *
	 * @return \SplFixedArray SplFixedArray of Bookmarks found or null if not found
	 *
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable are not the correct data type
	 *
	 * @see Example on Dylan's Github for Tweet Like: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Like.php
	 **/

	public static function getBookmarkByBookmarkProfileId(\PDO $pdo, string $bookmarkProfileId): \SPLFixedArray {
		try {
			$bookmarkProfileId = self::validateUuid($bookmarkProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		// create query template
		$query = "SELECT bookmarkArtId, bookmarkProfileId FROM bookmark WHERE bookmarkProfileId = :bookmarkProfileId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the placeholders in the template
		$parameters = ["bookmarkProfileId" => $bookmarkProfileId->getBytes()];
		$statement->execute($parameters);

		// build an array of bookmarks
		$bookmarks = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$bookmark = new Bookmark($row["bookmarkArtId"], $row["bookmarkProfileId"]);
				$bookmarks[$bookmarks->key()] = $bookmark;
				$bookmarks->next();
			} catch(\Exception $exception) {

				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($bookmarks);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["bookmarkArtId"] = $this->bookmarkArtId->toString();
		$fields["bookmarkProfileId"] = $this->bookmarkProfileId->toString();

		return ($fields);
	}

}
