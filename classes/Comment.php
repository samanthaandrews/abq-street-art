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

