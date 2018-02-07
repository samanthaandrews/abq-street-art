<?php
namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Comment;
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
	public final function setUp(): void {
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
	public function testInsertValidComment(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields to match our expectations
		$pdoComment = Comment::getCommentbyCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENTCONTENT);
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());

	}

	/**
	 * test inserting a Comment, editing it, and then updating it
	 */
	public function testUpdateValidComment(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//edit the Comment and update it in mySQL
		$comment->setCommentContent($this->VALID_COMMENTCONTENT2);
		$comment->update($this->getPDO());

		//grab the data from mySQL and enforce the fields to match our expectations
		$pdoComment = Comment::getCommentbyCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENTCONTENT);
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());

	}

	/**
	 * test creating a Comment and then deleting it
	 */
	public function testDeleteValidComment(): void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		// delete the Comment from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$comment->delete($this->getPDO());

		// take the data from mySQL and enforce that the Comment does not exist
		$pdoComment = Comment::getCommentByCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertNull($pdoComment);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("comment"));
	}

	/**
	 * test inserting a Comment and regrabbing it from mySQL
	 * TODO I am confused by how this grabs a profile bigger than allowable
	 */
	public function testGetInvalidCommentByCommentProfileId() : void {
		//grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $comment);
	}

	/**
	 * test grabbing a Comment by comment content
	 */
	public function testgetValidCommentByCommentContent() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce that the fields match our expectations
		$results = Comment::getCommentByCommentContent($this->getPDO(), $comment->getCommentContent());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);

		//enforce that no other objecs are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Comment", $results);

		//grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENTCONTENT);
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());
	}

	/**
	 * test grabbing a Comment by content that does not exist
	 **/
	public function testGetInvalidCommentByCommentContent() : void {
		//grab a comment by content that does not exist
		$comment = Comment::getCommentByCommentContent($this->getPDO()), "Snacks are overrated");
		$this->assertCount(0, $comment);
	}

	/**
	 * test grabbing all Comments
	 **/
	public function testGetAllValidComments() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce that the fields match our expectations
		$results = Comment::getAllComments($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Comment");


		//grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENTCONTENT);
		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());
	}
}
