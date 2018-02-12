<?php
/**
 * Created by PhpStorm.
 * User: schmi
 * Date: 2/6/2018
 * Time: 4:13 PM
 **/

namespace Edu\Cnm\AbqStreetArt\Test;

use Edu\Cnm\AbqStreetArt\Profile;


require_once (dirname(__DIR__, 1) . "/autoload.php");

// grab the uuid generator
require_once(dirname(__DIR__, 2) . "/lib/uuid.php");

/**
 * PHPUnit test for ABQ Street Art Profile class
 *
 *
 * @see \Edu\Cnm\AbqStreetArt\Profile
 * @author Mary MacMillan <mschmitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 **/
class ProfileTest extends StreetArtTest {

    /**
     * placeholder?? until we set up activation token
     * @var string $VALID_ACTIVATION
     **/
    protected $VALID_ACTIVATION;

    /**
     *
     * valid email to use
     * @var string $VALID_EMAIL
     **/
    protected $VALID_EMAIL = "schmitt.mary7@gmail.com";

    /**
     *
     * valid hash to use
     * @var string $VALID_HASH
     **/
    //TODO placeholder until we actually create these
    protected $VALID_HASH;

    /**
     *
     * valid user name to use
     * @var string $VALID_SALT
     **/
    protected $VALID_SALT;

    /**
     *
     * valid user name to use
     * @var string $VALID_USERNAME
     **/
    protected $VALID_USERNAME = "Mary";

    /**
     *
     *
     * second valid user name to use
     * @var string $VALID_USERNAME2
     **/

    protected $VALID_USERNAME2 = "Mary Ann";

    /**
     *
     * run the default setup operation to create salt and hash.
     **/
    //TODO do i need to change anything about this?
    public final function setUp() : void {
        parent::setUp();
        //
        $password = "thisismydogsname";
        $this->VALID_SALT = bin2hex(random_bytes(32));
        $this->VALID_HASH = hash_pbkdf2("sha512", $password, $this->VALID_SALT, 262144);
        $this->VALID_ACTIVATION = bin2hex(random_bytes(16));
    }

    /**
     * actual test!
     * insert valid profile and verify that mySQL data matches
     **/
    public function testInsertValidProfile() : void {
        //count number of rows and save for later
        $numRows = $this->getConnection()->getRowCount("profile");

        $profileId = generateUuidV4();

        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
    }

    /**
     * test inserting a Profile, editing it, and then updating it
     **/
    public function testUpdateValidProfile() {

        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");

        // create a new Profile and insert to into mySQL
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // edit the Profile and update it in mySQL
        $profile->setProfileUserName($this->VALID_USERNAME2);
        $profile->update($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME2);
    }

    /**
     * test creating a Profile and then deleting it
     **/
    public function testDeleteValidProfile() : void {
        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // delete the Profile from mySQL
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $profile->delete($this->getPDO());

        // grab the data from mySQL and enforce the Profile does not exist
        $pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
        $this->assertNull($pdoProfile);
        $this->assertEquals($numRows, $this->getConnection()->getRowCount("profile"));
    }

    /**
     * test inserting a Profile and regrabbing it from mySQL
     **/
    public function testGetValidProfileByProfileId() {

        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
    }

    /**
     * test grabbing a Profile that does not exist
     **/
    public function testGetInvalidProfileByProfileId() : void {

        // grab a profile id that exceeds the maximum allowable profile id
        $fakeProfileId = generateUuidV4();
        $profile = Profile::getProfileByProfileId($this->getPDO(), "$fakeProfileId" );
        $this->assertNull($profile);
    }

    public function testGetValidProfileByUserName() {

        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        //grab the data from MySQL
        $results = Profile::getProfileByProfileUserName($this->getPDO(), $this->VALID_USERNAME);
        $this->assertEquals($numRows +1, $this->getConnection()->getRowCount("profile"));

        //enforce no other objects are bleeding into profile
        $this->assertContainsOnlyInstancesOf("Edu\\CNM\\AbqStreetArt\\Profile", $results);

        //enforce the results meet expectations
        $pdoProfile = $results[0];
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
    }

    /**
     * test grabbing a Profile by username that does not exist
     **/
    public function testGetInvalidProfileByUserName() : void {

        // grab a user name that does not exist
        $profile = Profile::getProfileByProfileUserName($this->getPDO(), "Bad User Name");
        $this->assertCount(0, $profile);
    }

    /**
     * test grabbing a Profile by email
     **/
    public function testGetValidProfileByProfileEmail() {

        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoProfile = Profile::getProfileByProfileEmail($this->getPDO(), $profile->getProfileEmail());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileSalt(), $this->VALID_SALT);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
    }

    /**
     * test grabbing a Profile by an email that does not exists
     **/
    public function testGetInvalidProfileByProfileEmail() : void {

        // grab an email that does not exist
        $profile = Profile::getProfileByProfileEmail($this->getPDO(), "does@not.exist");
        $this->assertNull($profile);
    }

    /**
     * test grabbing a profile by its activation
     **/
    public function testGetValidProfileByActivationToken() {

        // count the number of rows and save it for later
        $numRows = $this->getConnection()->getRowCount("profile");
        $profileId = generateUuidV4();
        $profile = new Profile($profileId, $this->VALID_ACTIVATION, $this->VALID_EMAIL, $this->VALID_HASH, $this->VALID_SALT, $this->VALID_USERNAME);
        $profile->insert($this->getPDO());

        // grab the data from mySQL and enforce the fields match our expectations
        $pdoProfile = Profile::getProfileByProfileActivationToken($this->getPDO(), $profile->getProfileActivationToken());
        $this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
        $this->assertEquals($pdoProfile->getProfileId(), $profileId);
        $this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_ACTIVATION);
        $this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_EMAIL);
        $this->assertEquals($pdoProfile->getProfileHash(), $this->VALID_HASH);
        $this->assertEquals($pdoProfile->getProfileUserName(), $this->VALID_USERNAME);
    }

    /**
     * test grabbing a Profile by an activation token that does not exists
     **/
    public function testGetInvalidProfileActivationToken() : void {

        // grab an activation token that does not exist
        $profile = Profile::getProfileByProfileActivationToken($this->getPDO(), "5ebc7867885cb8dd25af05b991dd5609");
        $this->assertNull($profile);
    }
}