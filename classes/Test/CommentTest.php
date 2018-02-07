<?php
namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Profile;


require_once (dirname(__DIR__) . "/autoload.php");

//uuid generator goes here but I don't know what that is so I'm not gonna add it TODO add uuid generator

/**
 * PHPUnit test for ABQ Street Art Comment class
 *
 *
 * @see \Edu\Cnm\AbqStreetArt\Profile
 * @author Nathaniel Gustafson <natjgus@gmail.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/

class CommentTest extends StreetArtTest {
	/**
	 * Art that the comment is about; this is for foreign keys relations
	 *
	 * @var Art art
	 **/
	protected $art = null;
	/**
	 *Profile that created the comment; this is for foreign key relations
	 * @var Profile profile
	 * Prolifle is needed to make a comment, so we use it but set it to null
	 */
	protected $profile = null;

}