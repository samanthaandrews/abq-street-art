<?php
namespace Edu\Cnm\AbqStreetArt\Test;
use Edu\Cnm\AbqStreetArt\Art;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");
/**
 * Full PHPUnit test for the Art class
 *
 * This is a complete PHPUnit test of the Art class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Art
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @author Samantha Andrews <samantharaeandrews@gmail.com>
 **/
class ArtTest extends StreetArtTest {
	/**
	 * UUID of this art
	 * @var UUID|string $VALID_ARTID
	 **/
	protected $VALID_ARTID = "this is a valid id for this art";
	/**
	 * address of this art
	 * @var string $VALID_ARTADDRESS
	 **/
	protected $VALID_ARTADDRESS = "this is a valid address for this art";
	/**
	 * address of this art
	 * @var string $VALID_ARTADDRESS2
	 **/
	protected $VALID_ARTADDRESS2 = "this is still a valid address for this art";
	/**
	 * artist who made this art
	 * @var string $VALID_ARTARTIST
	 **/
	protected $VALID_ARTARTIST = "this is a valid artist for this piece of art";
	/**
	 * art image url
	 * @var string $VALID_ARTIMAGEURL
	 **/
	protected $VALID_ARTIMAGEURL = "this is a valid image url for this art";
	/**
	 * latitude coordinate for this art
	 * @var float $VALID_ARTLAT
	 **/
	protected $VALID_ARTLAT = "";
	/**
	 * location description for this art
	 * @var string $VALID_ARTLOCATION
	 **/
	protected $VALID_ARTLOCATION = "";
	/**
	 * longitude coordinate for this art
	 * @var float $VALID_ARTLONG
	 **/
	protected $VALID_ARTLONG = "";
	/**
	 * title for this art
	 * @var string $VALID_ARTTITLE
	 **/
	protected $VALID_ARTTITLE = "";
	/**
	 * type of art, i.e. mural, sculpture, etc.
	 * @var string $VALID_ARTTYPE
	 **/
	protected $VALID_ARTTYPE = "";
	/**
	 * year this art was installed/made
	 * @var string $VALID_ARTTYEAR
	 **/
	protected $VALID_ARTYEAR = "";
	/**
	 * create dependent objects before running each test
	 **/
	//TODO Do I need any of this setUp function?
	public final function setUp()  : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_SALT = bin2hex(random_bytes(32));
		$this->VALID_PROFILE_HASH = hash_pbkdf2("sha512", $password, $this->VALID_PROFILE_SALT, 262144);
		// create and insert a Profile to own the test Tweet
		$this->profile = new Profile(generateUuidV4(), null,"@handle", "test@phpunit.de",$this->VALID_PROFILE_HASH, "+12125551212", $this->VALID_PROFILE_SALT);
		$this->profile->insert($this->getPDO());
		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_TWEETDATE = new \DateTime();
		//format the sunrise date to use for testing
		$this->VALID_SUNRISEDATE = new \DateTime();
		$this->VALID_SUNRISEDATE->sub(new \DateInterval("P10D"));
		//format the sunset date to use for testing
		$this->VALID_SUNSETDATE = new\DateTime();
		$this->VALID_SUNSETDATE->add(new \DateInterval("P10D"));
	}
	/**
	 * test inserting a valid Art and verify that the actual mySQL data matches
	 **/
	public function testInsertValidArt() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Art and insert to into mySQL
		$artId = generateUuidV4();
		$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
		$art->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoArt = Art::getArtByArtId($this->getPDO(), $art->getArtId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("art"));
		//TODO does line 109 need a VALID_ARTID?
		$this->assertEquals($pdoArt->getArtId(), $artId);
		$this->assertEquals($pdoArt->getArtAddress(), $this->VALID_ARTADDRESS);
		$this->assertEquals($pdoArt->getArtArtist(), $this->VALID_ARTARTIST);
		$this->assertEquals($pdoArt->getArtImageUrl(), $this->VALID_ARTIMAGEURL);
		$this->assertEquals($pdoArt->getArtLat(), $this->VALID_ARTLAT);
		$this->assertEquals($pdoArt->getArtLocation(), $this->VALID_ARTLOCATION);
		$this->assertEquals($pdoArt->getArtLong(), $this->VALID_ARTLONG);
		$this->assertEquals($pdoArt->getArtTitle(), $this->VALID_ARTTITLE);
		$this->assertEquals($pdoArt->getArtType(), $this->VALID_ARTTYPE);
		$this->assertEquals($pdoArt->getArtYear(), $this->VALID_ARTYEAR);
	}
	/**
	 * test inserting an Art, editing it, and then updating it
	 **/
	public function testUpdateValidArt() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Art and insert to into mySQL
		$artId = generateUuidV4();
		$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
		$art->insert($this->getPDO());
		// edit the Art and update it in mySQL
		$art->setTweetContent($this->VALID_TWEETCONTENT2);
		$art->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoTweet = Tweet::getTweetByTweetId($this->getPDO(), $tweet->getTweetId());
		$this->assertEquals($pdoTweet->getTweetId(), $tweetId);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
		$this->assertEquals($pdoTweet->getTweetProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT2);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoTweet->getTweetDate()->getTimestamp(), $this->VALID_TWEETDATE->getTimestamp());
	}
	/**
	 * test creating an Art and then deleting it
	 **/
	public function testDeleteValidArt() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Tweet and insert to into mySQL
		$tweetId = generateUuidV4();
		$tweet = new Tweet($tweetId, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
		$tweet->insert($this->getPDO());
		// delete the Tweet from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
		$tweet->delete($this->getPDO());
		// grab the data from mySQL and enforce the Tweet does not exist
		$pdoTweet = Tweet::getTweetByTweetId($this->getPDO(), $tweet->getTweetId());
		$this->assertNull($pdoTweet);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("tweet"));
	}
	/**
	 * test grabbing a Tweet that does not exist
	 **/
	public function testGetInvalidTweetByTweetId() : void {
		// grab a profile id that exceeds the maximum allowable profile id
		$tweet = Tweet::getTweetByTweetId($this->getPDO(), generateUuidV4());
		$this->assertNull($tweet);
	}
	/**
	 * test inserting a Tweet and regrabbing it from mySQL
	 **/
	public function testGetValidTweetByTweetProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tweet");
		// create a new Tweet and insert to into mySQL
		$tweetId = generateUuidV4();
		$tweet = new Tweet($tweetId, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
		$tweet->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Tweet::getTweetByTweetProfileId($this->getPDO(), $tweet->getTweetProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Tweet", $results);
		// grab the result from the array and validate it
		$pdoTweet = $results[0];

		$this->assertEquals($pdoTweet->getTweetId(), $tweetId);
		$this->assertEquals($pdoTweet->getTweetProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoTweet->getTweetDate()->getTimestamp(), $this->VALID_TWEETDATE->getTimestamp());
	}
	/**
	 * test grabbing a Tweet that does not exist
	 **/
	public function testGetInvalidTweetByTweetProfileId() : void {
		// grab a profile id that exceeds the maximum allowable profile id
		$tweet = Tweet::getTweetByTweetProfileId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $tweet);
	}
	/**
	 * test grabbing a Tweet by tweet content
	 **/
	public function testGetValidTweetByTweetContent() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tweet");
		// create a new Tweet and insert to into mySQL
		$tweetId = generateUuidV4();
		$tweet = new Tweet($tweetId, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
		$tweet->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Tweet::getTweetByTweetContent($this->getPDO(), $tweet->getTweetContent());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
		$this->assertCount(1, $results);
		// enforce no other objects are bleeding into the test
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Tweet", $results);
		// grab the result from the array and validate it
		$pdoTweet = $results[0];
		$this->assertEquals($pdoTweet->getTweetId(), $tweetId);
		$this->assertEquals($pdoTweet->getTweetProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoTweet->getTweetDate()->getTimestamp(), $this->VALID_TWEETDATE->getTimestamp());
	}
	/**
	 * test grabbing a Tweet by content that does not exist
	 **/
	public function testGetInvalidTweetByTweetContent() : void {
		// grab a tweet by content that does not exist
		$tweet = Tweet::getTweetByTweetContent($this->getPDO(), "Comcast has the best service EVER #comcastLove");
		$this->assertCount(0, $tweet);
	}
	/**
	 * test grabbing all Tweets
	 **/
	public function testGetAllValidTweets() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tweet");
		// create a new Tweet and insert to into mySQL
		$tweetId = generateUuidV4();
		$tweet = new Tweet($tweetId, $this->profile->getProfileId(), $this->VALID_TWEETCONTENT, $this->VALID_TWEETDATE);
		$tweet->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Tweet::getAllTweets($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tweet"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DataDesign\\Tweet", $results);
		// grab the result from the array and validate it
		$pdoTweet = $results[0];
		$this->assertEquals($pdoTweet->getTweetId(), $tweetId);
		$this->assertEquals($pdoTweet->getTweetProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoTweet->getTweetContent(), $this->VALID_TWEETCONTENT);
		//format the date too seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoTweet->getTweetDate()->getTimestamp(), $this->VALID_TWEETDATE->getTimestamp());
	}
}