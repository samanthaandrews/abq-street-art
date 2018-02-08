<?php
namespace Edu\Cnm\AbqStreetArt\Test;
use Edu\Cnm\AbqStreetArt\Art;
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/autoload.php");
// grab the uuid generator
require_once(dirname(__DIR__) . "../lib/uuid.php");
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
	 * address of this art
	 * @var string $VALID_ARTADDRESS
	 **/
	protected $VALID_ARTADDRESS = "3900 Artsy Fartsy Lane NE";
	/**
	 * address of this art
	 * @var string $VALID_ARTADDRESS2
	 **/
	protected $VALID_ARTADDRESS2 = "this is still a valid address for this art";
	/**
	 * artist who made this art
	 * @var string $VALID_ARTARTIST
	 **/
	protected $VALID_ARTARTIST = "Mr. Art Man";
	/**
	 * art image url
	 * @var string $VALID_ARTIMAGEURL
	 **/
	protected $VALID_ARTIMAGEURL = "http://artsyfartsy.org";
	/**
	 * latitude coordinate for this art
	 * @var float $VALID_ARTLAT
	 **/
	protected $VALID_ARTLAT = 89;
	/**
	 * location description for this art
	 * @var string $VALID_ARTLOCATION
	 **/
	protected $VALID_ARTLOCATION = "in the alley where everyone pees";
	/**
	 * longitude coordinate for this art
	 * @var float $VALID_ARTLONG
	 **/
	protected $VALID_ARTLONG = 170;
	/**
	 * title for this art
	 * @var string $VALID_ARTTITLE
	 **/
	protected $VALID_ARTTITLE = "A Very Nice ART!";
	/**
	 * type of art, i.e. mural, sculpture, etc.
	 * @var string $VALID_ARTTYPE
	 **/
	protected $VALID_ARTTYPE = "mural";
	/**
	 * year this art was installed/made
	 * @var string $VALID_ARTTYEAR
	 **/
	protected $VALID_ARTYEAR = 1992;

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
		$art->setArtAddress($this->VALID_ARTADDRESS2);
		$art->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoArt = Art::getArtByArtId($this->getPDO(), $art->getArtId());
		$this->assertEquals($pdoArt->getArtId(), $artId);
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("art"));
		$this->assertEquals($pdoArt->getArtAddress(), $this->VALID_ARTADDRESS2);
	}
	/**
	 * test creating an Art and then deleting it
	 **/
	public function testDeleteValidArt() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Art and insert to into mySQL
		$artId = generateUuidV4();
		$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
		$art->insert($this->getPDO());
		// delete the Art from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("art"));
		$art->delete($this->getPDO());
		// grab the data from mySQL and enforce the Art does not exist
		$pdoArt = Art::getArtByArtId($this->getPDO(), $art->getArtId());
		$this->assertNull($pdoArt);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("art"));
	}
	/**
	 * test grabbing an Art that does not exist
	 **/
	public function testGetInvalidArtByArtId() : void {
		// grab an art id that exceeds the maximum allowable art id
		$art = Art::getArtByArtId($this->getPDO(), generateUuidV4());
		$this->assertNull($art);
	}

	/**
	 * test grabbing an Art by art distance
	 **/
	public function testGetValidArtByArtDistance() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Art and insert to into mySQL
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
	 * test grabbing an Art whose distance does not exist
	 **/
	public function testGetInvalidArtByArtDistance() : void {
		// grab a art by distance that does not exist
		$art = Art::getArtByArtDistance($this->getPDO(), "Comcast has the best service EVER #comcastLove");
		$this->assertCount(0, $art);
	}
	/**
	 * test grabbing an Art by year
	 **/
	public function testGetArtByArtYear() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("art");
		// create a new Art and insert to into mySQL
		$artId = generateUuidV4();
		$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
		$art->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Art::getArtByArtYear($this->getPDO(), $art->getArtYear());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("art"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\AbqStreetArt\\Art", $results);
		// grab the result from the array and validate it
		$pdoArt = $results[0];

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
}