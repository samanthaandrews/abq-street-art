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
	 * Profile is needed to make a comment, so we use it but set it to null
	 */
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $VALID_HASH
	 */
	protected $VALID_PROFILE_HASH;
	/**
	 * valid salt to use to create the profile object to own the test
	 * @var string $VALID_SALT
	 */
	protected $VALID_PROFILE_SALT;

	/**
	 * content of the Comment
	 * @var string $VALID_COMMENTCONTENT
	 **/
	protected $VALID_COMMENTCONTENT = "The PHPUnit test is passing for this comment";

	/**
	 * content of the updated Comment
	 * @var string $VALID_COMMENTCONTENT2
	 **/
	protected $VALID_COMMENTCONTENT2 = "The PHPUnit test is still passing for this comment";

	/**
	 ** This is the timestamp of the comment; this starts as null and is assigned later
	 **/
	protected $VALID_COMMENTDATETIME = null;

	/**
	 * Valid timestamp to use as sunriseCommentDate
	 **/
	protected $VALID_SUNRISEDATE = null;
	/**
	 * Valid timestamp to use as sunsetCommentDate
	 **/

	protected $VALID_SUNSETDATE = null;
	/**
	 * create dependent objects before running each test
	 **/

	/**
	 * create dependent objects before running each test
	 */
	public final function setUp() : void {
		//run the default setUp() method first
		parent::setUp();
		$password = "abc123";

	}
}