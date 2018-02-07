<?php
namespace Edu\Cnm\AbqStreetArt\Test;
use Edu\Cnm\AbqStreetArt\{Bookmark, Profile, Art};

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
/**
 * Full PHPUnit test for the Bookmark class
 *
 * This is a complete PHPUnit test of the Bookmark class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @author Erin Scott <erinleeannscott@gmail.com>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 *
 * @see Dylan's example on Github: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Test/LikeTest.php
 **/
class BookmarkTest extends StreetArtTest {

	/**
	 * Profile that created the bookmarked piece of Art; this is for foreign key relations
	 * @var  Profile $profile
	 **/
	protected $profile;

	/**
	 * Art that was bookmarked; this is for foreign key relations
	 * @var Art $art
	 **/
	protected $art;

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 */
	// placeholder until we actually create the following -Erin 2/7
	protected $VALID_HASH;

	/**
	 * timestamp of the Bookmark; this starts as null and is assigned later
	 * @var \DateTime $VALID_BOOKMARKDATE
	 **/
	protected $VALID_BOOKMARKDATE;

	/**
	 * valid salt to use to create the profile object to own the test
	 * @var string $VALID_SALT
	 */
	protected $VALID_SALT;

	/**
	 * valid activationToken to create the profile object to own the test
	 * @var string $VALID_ACTIVATION
	 */
	protected $VALID_ACTIVATION;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() : void {

		// run the default setUp() method first
		parent::setUp();

		// create a salt and hash for the mocked profile
		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		// create and insert the mocked profile
		$this->profile = new Profile(generateUuidV4(), null,"@phpunit", "test@phpunit.de",$this->VALID_HASH, "testUserName", $this->VALID_SALT);
		$this->profile->insert($this->getPDO());

		// create the and insert the mocked art
		$this->art = new Art(generateUuidV4(), $this->profile->getProfileId(), "PHPUnit bookmark test passing");
		$this->art->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_BOOKMARKDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Bookmark and verify that the actual mySQL data matches
	 **/
	public function testInsertValidBookmark() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->profile->getProfileId(), $this->bookmark->getArtId(), $this->VALID_BOOKMARKDATE);
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->profile->getProfileId(), $this->art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->bookmark->getArtId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoBookmark->getBookmarkDate()->getTimeStamp(), $this->VALID_BOOKMARKDATE->getTimestamp());
	}

	/**
	 * test creating a Bookmark and then deleting it
	 **/
	public function testDeleteValidBookmark() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->profile->getProfileId(), $this->art->getArtId(), $this->VALID_BOOKMARKDATE);
		$bookmark->insert($this->getPDO());

		// delete the Bookmark from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$bookmark->delete($this->getPDO());

		// grab the data from mySQL and enforce the Art does not exist
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->profile->getProfileId(), $this->art->getArtId());
		$this->assertNull($pdoBookmark);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("bookmark"));
	}

	/**
	 * test inserting a Bookmark and re-grabbing it from mySQL
	 **/
	public function testGetValidBookmarkByArtIdAndProfileId() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark$this->profile->getProfileId(), $this->art->getArtId(), $this->VALID_BOOKMARKDATE);
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->profile->getProfileId(), $this->art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoBookmark->getBookmarkDate()->getTimeStamp(), $this->VALID_BOOKMARKEDATE->getTimestamp());
	}

	/**
	 * test grabbing a Bookmark that does not exist
	 **/
	public function testGetInvalidBookmarkByArtIdAndProfileId() {

		// grab a art id and profile id that exceeds the maximum allowable art id and profile id
		$bookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), generateUuidV4(), generateUuidV4());
		$this->assertNull($bookmark);
	}

	/**
	 * test grabbing a Bookmark by art id
	 **/
	public function testGetValidBookmarkByArtId() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->profile->getProfileId(), $this->art->getArtId(), $this->VALID_BOOKMARKDATE);
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Bookmark::getBookmarkByBookmarkArtId($this->getPDO(), $this->art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\StreetArt\\Bookmark", $results);

		// grab the result from the array and validate it
		$pdoBookmark = $results[0];
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoBookmark->getBookmarkDate()->getTimeStamp(), $this->VALID_BOOKMARKDATE->getTimestamp());
	}

	/**
	 * test grabbing a Bookmark by an art id that does not exist
	 **/
	public function testGetInvalidBookmarkByArtId() : void {

		// grab a art id that exceeds the maximum allowable art id
		$bookmark = Bookmark::getBookmarkByBookmarkArtId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $bookmark);
	}

	/**
	 * test grabbing a Bookmark by profile id
	 **/
	public function testGetValidBookmarkByProfileId() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->profile->getProfileId(), $this->art->getArtId(), $this->VALID_BOOKMARKDATE);
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Bookmark::getBookmarkByBookmarkProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertCount(1, $results);

		// enforce that no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\StreetArt\\Bookmark", $results);

		// grab the result from the array and validate it
		$pdoBookmark = $results[0];
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoBookmark->getBookmarkeDate()->getTimeStamp(), $this->VALID_BOOKMARKDATE->getTimestamp());
	}

	/**
	 * test grabbing a Bookmark by a profile id that does not exist
	 **/
	public function testGetInvalidBookmarkByProfileId() : void {

		// grab a art id that exceeds the maximum allowable profile id
		$bookmark = Bookmark::getBookmarkByBookmarkProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $bookmark);
	}
}