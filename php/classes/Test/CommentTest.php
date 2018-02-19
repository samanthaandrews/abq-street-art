<?php
namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Comment;
use Edu\Cnm\AbqStreetArt\Profile;
use Edu\Cnm\AbqStreetArt\Art;
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
	 * @var Art $art
	 * Art is needed to be commented on, so we use it but set it to null
	 **/
	protected $art;

	/**
	 * Comment that the comment is about; this is for
	 *
	 * @var Comment $comment
	 * comment is needed to be commented on, so we use it but set it to null
	 **/
	protected $comment;
	/**
	 *Profile that created the comment; this is for foreign key relations
	 * @var Profile $profile
	 * Profile is needed to make a comment, so we use it but set it to null
	 */
	protected $profile;

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
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		//create and insert an Art object to own the Test
		//TODO figure out why Art is not defined

		$this->art = new Art(generateUuidV4(), "123 Main St.", "Artist Name", "www.art.com", 040.717274011, "side of building", 040.717274011, "Art Title", "Art Type", 2000);
		$this->art->insert($this->getPDO());

		//create and insert a Profile to own the Test
		//TODO figure out why phone number is in ProfileSalt
		$this->profile = new Profile(generateUuidV4(), $this->VALID_ACTIVATION, "natjgus@gmail.com", $this->VALID_PROFILE_HASH, $this->VALID_PROFILE_SALT, "@hamsterman");
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
		$comment = new Comment($commentId, $this->art->getArtId(), $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields to match our expectations
		//TODO Do I need to get this by art and profile id as well? they do for tweet, but tweet does not have primary key
		$pdoComment = Comment::getCommentbyCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentArtId(), $this->art->getArtId());
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
		$comment = new Comment($commentId, $this->art->getArtId(), $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//edit the Comment and update it in mySQL
		$comment->setCommentContent($this->VALID_COMMENTCONTENT2);
		$comment->update($this->getPDO());

		//grab the data from mySQL and enforce the fields to match our expectations
		$pdoComment = Comment::getCommentbyCommentId($this->getPDO(), $comment->getCommentId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentArtId(), $this->art->getArtId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoComment->getCommentContent(), $this->VALID_COMMENTCONTENT2);

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
		$comment = new Comment($commentId, $this->art->getArtId(), $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
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
	 * test grabbing a Comment that does not exsit
	 */
	public function testGetInvalidCommentByCommentArtId() : void {
		//grab an art id that exceeds the maximum allowable art id
		$comment = Comment::getCommentByCommentArtId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($comment);
	}

	/**
	 * test grabbing a Comment that does not exist
	 */
	public function testGetInvalidCommentByCommentProfileId() : void {
		//grab a profile id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentProfileId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($comment);
	}



	/**
	 * test grabbing a Comment that does not exist
	 **/
	public function testGetInvalidCommentbyCommentId() : void {
		//grab a comment id that exceeds the maximum allowable profile id
		$comment = Comment::getCommentByCommentId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($comment);
	}

	/**
	 * test grabbing a Comment by art id
	 */
	public function testGetValidCommentByArtId() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->art->getArtId(), $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce that the fields match our expectations
		$results = Comment::getCommentByCommentArtId($this->getPDO(), $this->art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Comment", $results);

		//grab the result from the array and validate it
		$pdoComment = $results[0];
		$this->assertEquals($pdoComment->getCommentId(), $commentId);
		$this->assertEquals($pdoComment->getCommentArtId(), $this->art->getArtId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());
	}

	/**
	 * test grabbing a Comment by an art id that does not exist
	 */
	public function testGetInvalidCommentByArtId() : void {
		//grab an art id that exceeds the maximum allowable art id
		$comment = Comment::getCommentByCommentArtId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($comment);
	}

	/**
	 * test grabbing a Comment by profile id
	 */
	public function testGetValidCommentByProfileId() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("comment");

		//create a new Comment and insert it into mySQL
		$commentId = generateUuidV4();
		$comment = new Comment($commentId, $this->art->getArtId(), $this->profile->getProfileId(), $this->VALID_COMMENTCONTENT, $this->VALID_COMMENTDATETIME);
		$comment->insert($this->getPDO());

		//grab the data from mySQL and enforce that the fields match our expectations
		$results = Comment::getCommentByCommentProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("comment"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Comment", $results);

		//grab the result from the array and validate it
		$pdoComment = $results[0];
		//TODO do I need to include this code?
		//$this->assertEquals($pdoComment->getCommentId(), $this->comment->getCommentId());
		$this->assertEquals($pdoComment->getCommentArtId(), $this->art->getArtId());
		$this->assertEquals($pdoComment->getCommentProfileId(), $this->profile->getProfileId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoComment->getCommentDateTime()->getTimestamp(), $this->VALID_COMMENTDATETIME->getTimestamp());

	}

	/**
	 * test grabbing a Comment by an profile id that does not exist
	 */
	public function testGetInvalidCommentByProfileId() : void {
		//grab an art id that exceeds the maximum allowable art id
		$comment = Comment::getCommentByCommentProfileId($this->getPDO(), generateUuidV4());
		$this->assertEmpty($comment);
	}


}
