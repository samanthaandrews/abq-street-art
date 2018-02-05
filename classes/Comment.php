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
 * @version 3.0.0
 **/

class Comment implements \JsonSerializable  {
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
}

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
	}
		//determine what exception type was thrown
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
public function getCommentId() : Uuid {
	return($this->commentId);
}

/**
 * mutator method for the comment id
 *
 * @param Uuid | string $newCommentId new value of comment id
 * @throws \RangeException if $newCommentId is not positive
 * @throws \TypeError if $newCommentId is not a uuid or string
 **/
public function setCommentId($newCommentId) : void {
	try {
		$
	}
}