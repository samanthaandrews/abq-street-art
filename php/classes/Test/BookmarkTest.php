<?php
namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Bookmark;
use Edu\Cnm\AbqStreetArt\Profile;
use Edu\Cnm\AbqStreetArt\Art;

// grab the class under scrutiny
require_once (dirname(__DIR__, 1) . "/autoload.php");
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

// grab the uuid generator
//require_once(dirname(__DIR__, 1) . "/ValidateUuid.php");
/**
 * Full PHPUnit test for the ABQ Street Art Bookmark class
 *
 * This is a complete PHPUnit test of the Bookmark class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @author Erin Scott <erinleeannscott@gmail.com>
 *
 * @see \Edu\Cnm\AbqStreetArt\Bookmark
 * @see Dylan's example on Github: https://github.com/deepdivedylan/data-design/blob/master/php/classes/Test/LikeTest.php
 * @see Bootcamp class example: https://bootcamp-coders.cnm.edu/class-materials/unit-testing/phpunit/
 **/
class BookmarkTest extends StreetArtTest {

	/**
	 * Art that was bookmarked; this is for foreign key relations
	 * We have to have a test Art in order for a Bookmark to exist.
	 *
	 * @var Art $art
	 **/
	protected $art;

	/**
	 * Profile that created the bookmarked piece of Art; this is for foreign key relations
	 * We have to have a test Profile in order for a Bookmark to exist.
	 *
	 * @var  Profile $profile
	 **/
	protected $profile;

	/**
	 * valid activationToken to create the profile object to own the test
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/
	protected $VALID_HASH;

	/**
	 * valid salt to use to create the profile object to own the test
	 * @var string $VALID_SALT
	 **/
	protected $VALID_SALT;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() : void {

		// run the default setUp() method first
		// you have to have the profile and art objects in order to have a bookmark object
		parent::setUp();

		// create a salt and hash for the mocked profile
		$password = "abc123";
		$this->VALID_SALT = bin2hex(random_bytes(32));
		$this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

		// create and insert the mocked art
		$this->art = new Art(generateUuidV4(), "12345 test street", "test artist name", "www.testimageurl.com", 69.69, "test description of location", 69.69, "test art title", "test art type", 1969);
		$this->art->insert($this->getPDO());

		// create and insert the mocked profile
		$this->profile = new Profile(generateUuidV4(), $this->VALID_ACTIVATION, "@emailTest", $this->VALID_HASH, $this->VALID_SALT, "test username");
		$this->profile->insert($this->getPDO());

	}

	/**
	 * the actual test!
	 * insert a valid Bookmark and verify that the actual mySQL data matches
	 **/
	public function testInsertValidBookmark() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->art->getArtId(), $this->profile->getProfileId());
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		// $pdoBookmark is an identical copy of the original $bookmark
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->art->getArtId(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
	}

	/**
	 * test creating a Bookmark and then deleting it
	 **/
	public function testDeleteValidBookmark() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->art->getArtId(), $this->profile->getProfileId());
		$bookmark->insert($this->getPDO());

		// delete the Bookmark from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$bookmark->delete($this->getPDO());

		// grab the data from mySQL and enforce the Art does not exist
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->art->getArtId(), $this->profile->getProfileId());
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
		$bookmark = new Bookmark($this->art->getArtId(), $this->profile->getProfileId());
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoBookmark = Bookmark::getBookmarkByBookmarkArtIdAndBookmarkProfileId($this->getPDO(), $this->art->getArtId(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
	}

	/**
	 * test grabbing a Bookmark that does not exist
	 **/
	public function testGetInvalidBookmarkByArtIdAndProfileId() : void {

		// grab an art id and a profile id that exceeds the maximum allowable art id and profile id
		//TODO confirm with group that what I have below is correct -Erin 2/7
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
		$bookmark = new Bookmark($this->art->getArtId(), $this->profile->getProfileId());
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Bookmark::getBookmarkByBookmarkArtId($this->getPDO(), $this->art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Bookmark", $results);

		// grab the result from the array and validate it
		$pdoBookmark = $results[0];
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
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
	 * test grabbing a Bookmark by a profile id
	 **/
	public function testGetValidBookmarkByProfileId() : void {

		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("bookmark");

		// create a new Bookmark and insert to into mySQL
		$bookmark = new Bookmark($this->art->getArtId(), $this->profile->getProfileId());
		$bookmark->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Bookmark::getBookmarkByBookmarkProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("bookmark"));
		$this->assertCount(1, $results);

		// enforce that no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Bookmark", $results);

		// grab the result from the array and validate it
		$pdoBookmark = $results[0];
		$this->assertEquals($pdoBookmark->getBookmarkArtId(), $this->art->getArtId());
		$this->assertEquals($pdoBookmark->getBookmarkProfileId(), $this->profile->getProfileId());
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
