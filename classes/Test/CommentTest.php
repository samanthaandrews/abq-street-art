<?php
namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Profile;
use function Sodium\randombytes_buf;


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
		$this->VALID_PROFILE_SALT = bin2hex(random_bytes(32));
		$this->VALID_PROFILE_HASH = hash_pbkdf2("sha512", $password, $this->VALID_PROFILE_SALT, 262144);

		//create and insert a Profile to own the Test
		//TODO figure out why phone number is in ProfileSalt
		$this->profile = new Profile(generateUuidV4(), null, "natjgus@gmail.com", $this->VALID_PROFILE_HASH, "+15054122437", $this->VALID_PROFILE_SALT);
		$this->profile->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_COMMENTDATETIME = new \DateTime();

		//format the sunrise date to use for testing
		$this->VALID_SUNRISEDATE = new \DateTime();
		$this->VALID_SUNRISEDATE->sub(new \DateInterval("P10D"));

		//format the sunset date to use for testing
		$this->VALID_SUNSETDATE = new\DateTime();
		$this->VALID_SUNSETDATE->add(new \DateInterval("P10D"));
	}

	/**
	 * test inserting a valid Comment and verify that mySQL data matches
	 */
	public function testInsertValidComment() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$profile->insert($this->getPDO());


	}









































}
