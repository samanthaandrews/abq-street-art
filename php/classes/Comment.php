<?php
namespace Edu\Cnm\AbqStreetArt;


require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is the cross section for the Comment class of ABQ Street Art
 *
 * The Comment class of ABQ Street Art webpage
 *
 * @author Nathaniel Gustafson <natjgus@gmail.com>
 **/

class Comment implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * id for this Comment; this is the primary key
	 * @var Uuid $commentId
	 **/
	private $commentId;
	/**
	 * id for the Art piece that the comment was made on; this is a foreign key
	 * @var Uuid $commentArtId
	 **/
	private $commentArtId;
	/**
	 * id for the Profile that made the comment; this is a foreign key
	 * @var Uuid $commentProfileId
	 **/
	private $commentProfileId;
	/**
	 * content of the Comment
	 * @var string $commentContent
	 **/
	private $commentContent;
	/**
	 * date and time this Comment was posted, in a PHP DateTime object
	 * @var \DateTime $commentDateTime
	 **/
	private $commentDateTime;


	/**
	 * constructor for this Comment
	 *
	 * @param string|Uuid $newCommentId id of this Comment or null if a new Comment
	 * @param string|Uuid $newCommentArtId id of the Art piece the comment was made on
	 * @param string|Uuid $newCommentProfileId id of the Profile that made the Comment
	 * @param string $newCommentContent string containing actual Comment data
	 * @param \DateTime|string|null $newCommentDateTime date and time when Comment was sent or null if set to current date and time
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/
	public function __construct($newCommentId, $newCommentArtId, $newCommentProfileId, string $newCommentContent, $newCommentDateTime = null) {
		try {
			$this->setCommentId($newCommentId);
			$this->setCommentArtId($newCommentArtId);
			$this->setCommentProfileId($newCommentProfileId);
			$this->setCommentContent($newCommentContent);
			$this->setCommentDateTime($newCommentDateTime);
		} //determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for comment id
	 *
	 * @return Uuid value of comment id
	 **/
	public function getCommentId(): Uuid {
		return ($this->commentId);
	}

	/**
	 * mutator method for the comment id
	 *
	 * @param Uuid | string $newCommentId new value of comment id
	 * @throws \RangeException if $newCommentId is not positive
	 * @throws \TypeError if $newCommentId is not a uuid or string
	 **/
	public function setCommentId($newCommentId): void {
		try {
			$uuid = self::validateUuid($newCommentId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		//convert and store the comment id
		$this->commentId = $uuid;
	}

	/**
	 * accessor method for comment art id
	 *
	 * @return Uuid value of comment art id
	 **/
	public function getCommentArtId(): Uuid {
		return ($this->commentArtId);
	}

	/**
	 * mutator method for comment art id
	 *
	 * @param string | Uuid $newCommentArtId new value of comment art id
	 * @throws \RangeException if $newCommentArtId is not positive
	 * @throws \TypeError if $newCommentArtId is not an integer
	 **/
	public function setCommentArtId($newCommentArtId): void {
		try {
			$uuid = self::validateUuid($newCommentArtId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->commentArtId = $uuid;
	}

	/**
	 * accessor method for comment profile id
	 *
	 * @return Uuid value of comment profile id
	 **/
	public function getCommentProfileId(): Uuid {
		return ($this->commentProfileId);
	}

	/**
	 * mutator method for comment profile id
	 *
	 * @param string | Uuid $newCommentProfileId new value of comment profile id
	 * @throws \RangeException if $newCommentProfileId is not positive
	 * @throws \TypeError if $newCommentProfileId is not an integer
	 **/
	public function setCommentProfileId($newCommentProfileId): void {
		try {
			$uuid = self::validateUuid($newCommentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the profile id
		$this->commentProfileId = $uuid;
	}

	/**
	 * accessor method for comment content
	 *
	 * @return string value of comment content
	 **/
	public function getCommentContent(): string {
		return ($this->commentContent);
	}

	/**
	 * mutator method for comment content
	 *
	 * @param string $newCommentContent new value of the comment content
	 * @throws \InvalidArgumentException if $newCommentContent is not a string or insecure
	 * @throws \RangeException if $newCommentContent is > 4096 characters
	 * @throws \TypeError if $newCommentContent is not a string
	 **/
	public function setCommentContent(string $newCommentContent): void {
		// verify the comment content is secure
		$newCommentContent = trim($newCommentContent);
		$newCommentContent = filter_var($newCommentContent, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newCommentContent) === true) {
			throw(new \InvalidArgumentException("comment content is empty or insecure"));
		}

		// verify the comment content will fit in the database
		if(strlen($newCommentContent) > 4096) {
			throw(new \RangeException("comment content too large"));
		}

		// store the comment content
		$this->commentContent = $newCommentContent;
	}


	/**
	 * accessor method for comment date time
	 *
	 * @return \DateTime value of comment date
	 **/
	public function getCommentDateTime(): \DateTime {
		return ($this->commentDateTime);
	}

	/**
	 * mutator method for the comment date time
	 *
	 * @param \DateTime|string|null $newCommentDateTime comment date as a DateTime object or string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newCommentDateTime is not a valid object or string
	 * @throws \RangeException if $newCommentDateTime is a date that does not exist
	 * @throw \TypeError if returned type does not match
	 **/
	public function setCommentDateTime($newCommentDateTime = null): void {
		// base case: if the date is null, use the current date and time
		if($newCommentDateTime === null) {
			$this->commentDateTime = new \DateTime();
			return;
		}

		// store the comment date using the ValidateDate trait
		try {
			$newCommentDateTime = self::validateDateTime($newCommentDateTime);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		$this->commentDateTime = $newCommentDateTime;
	}

	/**
	 * inserts this Comment into mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo): void {

		// create query template
		$query = "INSERT INTO comment(commentId, commentArtId, commentProfileId, commentContent, commentDateTime) VALUES(:commentId, :commentArtId, :commentProfileId, :commentContent, :commentDateTime)";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holders in the template
		$formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentArtId" => $this->commentArtId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * deletes this Comment from mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo): void {

		// create query template
		$query = "DELETE FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		// bind the member variables to the place holder in the template
		$parameters = ["commentId" => $this->commentId->getBytes()];
		$statement->execute($parameters);
	}


	/**
	 * updates this Comment in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related error occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo): void {

		//create a query template
		$query = "UPDATE comment SET commentArtId = :commentArtId, commentProfileId = :commentProfileId, commentContent = :commentContent, commentDateTime = :commentDateTime WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		$formattedDate = $this->commentDateTime->format("Y-m-d H:i:s.u");
		$parameters = ["commentId" => $this->commentId->getBytes(), "commentArtId" => $this->commentArtId->getBytes(), "commentProfileId" => $this->commentProfileId->getBytes(), "commentContent" => $this->commentContent, "commentDateTime" => $formattedDate];
		$statement->execute($parameters);
	}

	/**
	 * gets the Comment by commentId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $commentId comment id to search for
	 * @return Comment|null Comment found or null if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getCommentByCommentId(\PDO $pdo, $commentId): ?Comment {
		// sanitize the commentId before searching
		try {
			$commentId = self::validateUuid($commentId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT commentId, commentArtId, commentProfileId, commentContent, commentDateTime FROM comment WHERE commentId = :commentId";
		$statement = $pdo->prepare($query);

		//bind the comment id to the place holder in the template
		$parameters = ["commentId" => $commentId->getBytes()];
		$statement->execute($parameters);

		//grab the comment from mySQL
		try {
			$comment = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$comment = new Comment($row["commentId"], $row["commentArtId"], $row["commentProfileId"], $row["commentContent"], $row["commentDateTime"]);
			}
		} catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($comment);
	}

	/**
	 * gets the Comment by commentArtId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $commentArtId comment art id to search for
	 * @return \SplFixedArray array of comments found or empty if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getCommentByCommentArtId(\PDO $pdo, $commentArtId): \SplFixedArray {
		// sanitize the commentArtId before searching
		try {
			$commentArtId = self::validateUuid($commentArtId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT commentId, commentArtId, commentProfileId, commentContent, commentDateTime FROM comment WHERE commentArtId = :commentArtId";
		$statement = $pdo->prepare($query);

		//bind the comment id to the place holder in the template
		$parameters = ["commentArtId" => $commentArtId->getBytes()];
		$statement->execute($parameters);

//		//build and array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentArtId"], $row["commentProfileId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	/**
	 * gets the Comment by commentProfileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param Uuid|string $commentProfileId comment profile id to search for
	 * @return \SplFixedArray array of comments found or empty if not found
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when a variable is not the correct data type
	 **/
	public static function getCommentByCommentProfileId(\PDO $pdo, $commentProfileId): \SplFixedArray {
		// sanitize the commentProfileId before searching
		try {
			$commentProfileId = self::validateUuid($commentProfileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT commentId, commentArtId, commentProfileId, commentContent, commentDateTime FROM comment WHERE commentProfileId = :commentProfileId";
		$statement = $pdo->prepare($query);

		//bind the comment id to the place holder in the template
		$parameters = ["commentProfileId" => $commentProfileId->getBytes()];
		$statement->execute($parameters);

		//build and array of comments
		$comments = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while (($row = $statement->fetch()) !== false) {
			try {
				$comment = new Comment($row["commentId"], $row["commentArtId"], $row["commentProfileId"], $row["commentContent"], $row["commentDateTime"]);
				$comments[$comments->key()] = $comment;
				$comments->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return ($comments);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize(): array {
		$fields = get_object_vars($this);

		$fields["commentId"] = $this->commentId->toString();
		$fields["commentArtId"] = $this->commentArtId->toString();
		$fields["commentProfileId"] = $this->commentProfileId->toString();

		//format the date so that the front end can consume it
		$fields["commentDateTime"] = round(floatval($this->commentDateTime->format("U.u")) * 1000);
		return ($fields);
	}

}

